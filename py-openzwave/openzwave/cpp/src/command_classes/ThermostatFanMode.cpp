//-----------------------------------------------------------------------------
//
//	ThermostatFanMode.cpp
//
//	Implementation of the Z-Wave COMMAND_CLASS_THERMOSTAT_FAN_MODE
//
//	Copyright (c) 2010 Mal Lansell <openzwave@lansell.org>
//
//	SOFTWARE NOTICE AND LICENSE
//
//	This file is part of OpenZWave.
//
//	OpenZWave is free software: you can redistribute it and/or modify
//	it under the terms of the GNU Lesser General Public License as published
//	by the Free Software Foundation, either version 3 of the License,
//	or (at your option) any later version.
//
//	OpenZWave is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU Lesser General Public License for more details.
//
//	You should have received a copy of the GNU Lesser General Public License
//	along with OpenZWave.  If not, see <http://www.gnu.org/licenses/>.
//
//-----------------------------------------------------------------------------

#include <string>
#include "CommandClasses.h"
#include "ThermostatFanMode.h"
#include "Defs.h"
#include "Msg.h"
#include "Node.h"
#include "Driver.h"
#include "Log.h"

#include "ValueList.h"

using namespace OpenZWave;

enum ThermostatFanModeCmd
{
	ThermostatFanModeCmd_Set				= 0x01,
	ThermostatFanModeCmd_Get				= 0x02,
	ThermostatFanModeCmd_Report				= 0x03,
	ThermostatFanModeCmd_SupportedGet		= 0x04,
	ThermostatFanModeCmd_SupportedReport	= 0x05
};

static string const c_modeName[] = 
{
	"Auto Low",
	"On Low",
	"Auto High",
	"On High"
};

//-----------------------------------------------------------------------------
// <ThermostatFanMode::ReadXML>
// Read the supported modes
//-----------------------------------------------------------------------------
void ThermostatFanMode::ReadXML
( 
	TiXmlElement const* _ccElement
)
{
	CommandClass::ReadXML( _ccElement );

	if( Node* node = GetNodeUnsafe() )
	{
		vector<ValueList::Item>	supportedModes;

		TiXmlElement const* supportedModesElement = _ccElement->FirstChildElement( "SupportedModes" );
		if( supportedModesElement )
		{
			TiXmlElement const* modeElement = supportedModesElement->FirstChildElement();
			while( modeElement )
			{
				char const* str = modeElement->Value();
				if( str && !strcmp( str, "Mode" ) )
				{
					int index;
					if( TIXML_SUCCESS == modeElement->QueryIntAttribute( "index", &index ) )
					{
						ValueList::Item item;
						item.m_value = index;
						item.m_label = c_modeName[index];
						supportedModes.push_back( item );					
					}
				}

				modeElement = modeElement->NextSiblingElement();
			}
		}

		if( !supportedModes.empty() )
		{
			m_supportedModes = supportedModes;
			ClearStaticRequest( StaticRequest_Values );
			CreateVars( 1 ); 
		}
	}
}

//-----------------------------------------------------------------------------
// <ThermostatFanMode::WriteXML>
// Save the supported modes
//-----------------------------------------------------------------------------
void ThermostatFanMode::WriteXML
( 
	TiXmlElement* _ccElement
)
{
	CommandClass::WriteXML( _ccElement );

	if( Node* node = GetNodeUnsafe() )
	{
		TiXmlElement* supportedModesElement = new TiXmlElement( "SupportedModes" );
		_ccElement->LinkEndChild( supportedModesElement );

		for( vector<ValueList::Item>::iterator it = m_supportedModes.begin(); it != m_supportedModes.end(); ++it )
		{
			ValueList::Item const& item = *it;

			TiXmlElement* modeElement = new TiXmlElement( "Mode" );
			supportedModesElement->LinkEndChild( modeElement );

			char str[8];
			snprintf( str, 8, "%d", item.m_value );
			modeElement->SetAttribute( "index", str );
			modeElement->SetAttribute( "label", item.m_label.c_str() );
		}
	}
}

//-----------------------------------------------------------------------------
// <ThermostatFanMode::RequestState>
// Get the static thermostat fan mode details from the device
//-----------------------------------------------------------------------------
bool ThermostatFanMode::RequestState
(
	uint32 const _requestFlags
)
{
	bool requests = false;
	if( ( _requestFlags & RequestFlag_Static ) && HasStaticRequest( StaticRequest_Values ) )
	{
		// Request the supported modes
		RequestValue( ThermostatFanModeCmd_SupportedGet );
		requests = true;
	}

	if( _requestFlags & RequestFlag_Dynamic )
	{
		// Request the current fan mode
		RequestValue( ThermostatFanModeCmd_Get );
		requests = true;
	}

	return requests;
}

//-----------------------------------------------------------------------------
// <ThermostatFanMode::RequestValue>
// Get the thermostat fan mode details from the device
//-----------------------------------------------------------------------------
void ThermostatFanMode::RequestValue
(
	uint8 const _getTypeEnum,
	uint8 const _dummy			// = 0 (not used)
)
{
	if( _getTypeEnum == ThermostatFanModeCmd_SupportedGet )
	{
		// Request the supported modes
		Msg* msg = new Msg( "Request Supported Thermostat Fan Modes", GetNodeId(), REQUEST, FUNC_ID_ZW_SEND_DATA, true, true, FUNC_ID_APPLICATION_COMMAND_HANDLER, GetCommandClassId() );
		msg->Append( GetNodeId() );
		msg->Append( 2 );
		msg->Append( GetCommandClassId() );
		msg->Append( ThermostatFanModeCmd_SupportedGet );
		msg->Append( TRANSMIT_OPTION_ACK | TRANSMIT_OPTION_AUTO_ROUTE );
		GetDriver()->SendMsg( msg );
		return;
	}

	if( _getTypeEnum == ThermostatFanModeCmd_Get || _getTypeEnum == 0 )
	{
		// Request the current fan mode
		Msg* msg = new Msg( "Request Current Thermostat Fan Mode", GetNodeId(), REQUEST, FUNC_ID_ZW_SEND_DATA, true, true, FUNC_ID_APPLICATION_COMMAND_HANDLER, GetCommandClassId() );
		msg->Append( GetNodeId() );
		msg->Append( 2 );
		msg->Append( GetCommandClassId() );
		msg->Append( ThermostatFanModeCmd_Get );
		msg->Append( TRANSMIT_OPTION_ACK | TRANSMIT_OPTION_AUTO_ROUTE );
		GetDriver()->SendMsg( msg );
		return;
	}
}

//-----------------------------------------------------------------------------
// <ThermostatFanMode::HandleMsg>
// Handle a message from the Z-Wave network
//-----------------------------------------------------------------------------
bool ThermostatFanMode::HandleMsg
(
	uint8 const* _data,
	uint32 const _length,
	uint32 const _instance	// = 1
)
{
	if( ThermostatFanModeCmd_Report == (ThermostatFanModeCmd)_data[0] )
	{
		if( !m_supportedModes.empty() )
		{
			// We have received the thermostat mode from the Z-Wave device
			if( ValueList* valueList = static_cast<ValueList*>( GetValue( _instance, 0 ) ) )
			{
				valueList->OnValueChanged( (int32)_data[1] );
				Log::Write( "Received thermostat fan mode from node %d: %s", GetNodeId(), valueList->GetItem().m_label.c_str() );		
			}
		}
		return true;
	}
	
	if( ThermostatFanModeCmd_SupportedReport == (ThermostatFanModeCmd)_data[0] )
	{
		// We have received the supported thermostat fan modes from the Z-Wave device
		Log::Write( "Received supported thermostat fan modes from node %d", GetNodeId() );		

		m_supportedModes.clear();
		for( uint32 i=1; i<_length-1; ++i )
		{
			for( int32 bit=0; bit<8; ++bit )
			{
				if( ( _data[i] & (1<<bit) ) != 0 )
				{
					ValueList::Item item;
					item.m_value = (int32)((i-1)<<3) + bit;
					item.m_label = c_modeName[item.m_value];
					m_supportedModes.push_back( item );

					Log::Write( "    Added fan mode: %s", c_modeName[item.m_value].c_str() );
				}
			}
		}

		ClearStaticRequest( StaticRequest_Values );
		CreateVars( _instance );
		return true;
	}

	return false;
}

//-----------------------------------------------------------------------------
// <ThermostatFanMode::SetValue>
// Set the device's thermostat fan mode
//-----------------------------------------------------------------------------
bool ThermostatFanMode::SetValue
(
	Value const& _value
)
{
	if( ValueID::ValueType_List == _value.GetID().GetType() )
	{
		ValueList const* value = static_cast<ValueList const*>(&_value);
		uint8 state = (uint8)value->GetItem().m_value;

		Msg* msg = new Msg( "Set Thermostat Fan Mode", GetNodeId(), REQUEST, FUNC_ID_ZW_SEND_DATA, true );
		msg->Append( GetNodeId() );
		msg->Append( 3 );
		msg->Append( GetCommandClassId() );
		msg->Append( ThermostatFanModeCmd_Set );
		msg->Append( state );
		msg->Append( TRANSMIT_OPTION_ACK | TRANSMIT_OPTION_AUTO_ROUTE );
		GetDriver()->SendMsg( msg );
		return true;
	}

	return false;
}

//-----------------------------------------------------------------------------
// <ThermostatFanMode::CreateVars>
// Create the values managed by this command class
//-----------------------------------------------------------------------------
void ThermostatFanMode::CreateVars
(
	uint8 const _instance
)
{
	if( m_supportedModes.empty() )
	{
		return;
	}

	if( Node* node = GetNodeUnsafe() )
	{
		node->CreateValueList( ValueID::ValueGenre_User, GetCommandClassId(), _instance, 0, "Fan Mode", "", false, m_supportedModes, m_supportedModes[0].m_value );
	}
}

