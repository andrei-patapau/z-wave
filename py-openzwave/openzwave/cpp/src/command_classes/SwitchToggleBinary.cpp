//-----------------------------------------------------------------------------
//
//	SwitchToggleBinary.cpp
//
//	Implementation of the Z-Wave COMMAND_CLASS_SWITCH_TOGGLE_BINARY
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

#include "CommandClasses.h"
#include "SwitchToggleBinary.h"
#include "Defs.h"
#include "Msg.h"
#include "Driver.h"
#include "Node.h"
#include "Log.h"

#include "ValueBool.h"

using namespace OpenZWave;

enum SwitchToggleBinaryCmd
{
	SwitchToggleBinaryCmd_Set		= 0x01,
	SwitchToggleBinaryCmd_Get		= 0x02,
	SwitchToggleBinaryCmd_Report	= 0x03
};

//-----------------------------------------------------------------------------
// <SwitchToggleBinary::RequestState>												   
// Request current state from the device									   
//-----------------------------------------------------------------------------
bool SwitchToggleBinary::RequestState
(
	uint32 const _requestFlags
)
{
	if( _requestFlags & RequestFlag_Dynamic )
	{
		RequestValue();
		return true;
	}

	return false;
}

//-----------------------------------------------------------------------------
// <SwitchToggleBinary::RequestValue>												   
// Request current value from the device									   
//-----------------------------------------------------------------------------
void SwitchToggleBinary::RequestValue
(
	uint8 const _dummy1,	// = 0 (not used)
	uint8 const _dummy2		// = 0 (not used)
)
{
	Msg* msg = new Msg( "SwitchToggleBinaryCmd_Get", GetNodeId(), REQUEST, FUNC_ID_ZW_SEND_DATA, true, true, FUNC_ID_APPLICATION_COMMAND_HANDLER, GetCommandClassId() );
	msg->Append( GetNodeId() );
	msg->Append( 2 );
	msg->Append( GetCommandClassId() );
	msg->Append( SwitchToggleBinaryCmd_Get );
	msg->Append( TRANSMIT_OPTION_ACK | TRANSMIT_OPTION_AUTO_ROUTE );
	GetDriver()->SendMsg( msg );
}

//-----------------------------------------------------------------------------
// <SwitchToggleBinary::HandleMsg>
// Handle a message from the Z-Wave network
//-----------------------------------------------------------------------------
bool SwitchToggleBinary::HandleMsg
(
	uint8 const* _data,
	uint32 const _length,
	uint32 const _instance	// = 1
)
{
	if( SwitchToggleBinaryCmd_Report == (SwitchToggleBinaryCmd)_data[0] )
	{
		Log::Write( "Received SwitchToggleBinary report from node %d: %s", GetNodeId(), _data[1] ? "On" : "Off" );

		if( ValueBool* value = static_cast<ValueBool*>( GetValue( _instance, 0 ) ) )
		{
			value->OnValueChanged( _data[1] != 0 );
		}
		return true;
	}

	return false;
}

//-----------------------------------------------------------------------------
// <SwitchToggleBinary::SetValue>
// Toggle the state of the switch
//-----------------------------------------------------------------------------
bool SwitchToggleBinary::SetValue
(
	Value const& _value
)
{
	Log::Write( "SwitchToggleBinary::Set - Toggling the state of node %d", GetNodeId() );
	Msg* msg = new Msg( "SwitchToggleBinary Set", GetNodeId(), REQUEST, FUNC_ID_ZW_SEND_DATA, true );		
	msg->Append( GetNodeId() );
	msg->Append( 2 );
	msg->Append( GetCommandClassId() );
	msg->Append( SwitchToggleBinaryCmd_Set );
	msg->Append( TRANSMIT_OPTION_ACK | TRANSMIT_OPTION_AUTO_ROUTE );
	GetDriver()->SendMsg( msg );
	return true;
}

//-----------------------------------------------------------------------------
// <SwitchToggleBinary::CreateVars>
// Create the values managed by this command class
//-----------------------------------------------------------------------------
void SwitchToggleBinary::CreateVars
(
	uint8 const _instance
)
{
	if( Node* node = GetNodeUnsafe() )
	{
		node->CreateValueBool( ValueID::ValueGenre_User, GetCommandClassId(), _instance, 0, "Toggle Switch", "", false, false );
	}
}


