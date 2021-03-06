//-----------------------------------------------------------------------------
//
//	MultiInstance.cpp
//
//	Implementation of the Z-Wave COMMAND_CLASS_MULTI_INSTANCE
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
#include "MultiInstance.h"
#include "Defs.h"
#include "Msg.h"
#include "Driver.h"
#include "Node.h"
#include "Log.h"

using namespace OpenZWave;


//-----------------------------------------------------------------------------
// <MultiInstance::RequestInstances>												   
// Request number of instances of the specified command class from the device									   
//-----------------------------------------------------------------------------
bool MultiInstance::RequestInstances
(
	CommandClass const* _commandClass
)
{
	if( _commandClass->HasStaticRequest( StaticRequest_Instances ) )
	{
		char str[128];
		snprintf( str, 128, "MultiInstanceCmd_Get for %s", _commandClass->GetCommandClassName().c_str() );

		Msg* msg = new Msg( str, GetNodeId(), REQUEST, FUNC_ID_ZW_SEND_DATA, true, true, FUNC_ID_APPLICATION_COMMAND_HANDLER, GetCommandClassId() );
		msg->Append( GetNodeId() );
		msg->Append( 3 );
		msg->Append( GetCommandClassId() );
		msg->Append( MultiInstanceCmd_Get );
		msg->Append( _commandClass->GetCommandClassId() );
		msg->Append( TRANSMIT_OPTION_ACK | TRANSMIT_OPTION_AUTO_ROUTE );
		GetDriver()->SendMsg( msg );
		return true;
	}

	return false;
}

//-----------------------------------------------------------------------------
// <MultiInstance::HandleMsg>
// Handle a message from the Z-Wave network
//-----------------------------------------------------------------------------
bool MultiInstance::HandleMsg
(
	uint8 const* _data,
	uint32 const _length,
	uint32 const _instance	// = 1
)
{
	bool handled = false;
	if( Node* node = GetNodeUnsafe() )
	{
		if( MultiInstanceCmd_Report == (MultiInstanceCmd)_data[0] )
		{
			uint8 commandClassId = _data[1];
			uint8 instances = _data[2];

			if( CommandClass* pCommandClass = node->GetCommandClass( commandClassId ) )
			{
				Log::Write( "Received instance-count from node %d for %s: %d", GetNodeId(), pCommandClass->GetCommandClassName().c_str(), instances );
				pCommandClass->SetInstances( instances );
				pCommandClass->ClearStaticRequest( StaticRequest_Instances );
			}
			
			handled = true;
		}
		else if( MultiInstanceCmd_CmdEncap == (MultiInstanceCmd)_data[0] )
		{
			uint8 instance = _data[1];
			uint8 commandClassId = _data[2];

			if( CommandClass* pCommandClass = node->GetCommandClass( commandClassId ) )
			{
				Log::Write( "Received a multi-instance encapsulated command from node %d: Command Class %s, Instance=%d", GetNodeId(), pCommandClass->GetCommandClassName().c_str(), instance );
				pCommandClass->HandleMsg( &_data[3], _length-3, instance );
			}

			handled = true;
		}
	}

	return handled;
}

