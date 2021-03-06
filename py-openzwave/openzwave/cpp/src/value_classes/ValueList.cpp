//-----------------------------------------------------------------------------
//
//	ValueList.cpp
//
//	Represents a list of items
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

#include "tinyxml.h"
#include "ValueList.h"
#include "Msg.h"
#include "Log.h"

using namespace OpenZWave;


//-----------------------------------------------------------------------------
// <ValueList::ValueList>
// Constructor
//-----------------------------------------------------------------------------
ValueList::ValueList
(
	uint32 const _homeId,
	uint8 const _nodeId,
	ValueID::ValueGenre const _genre,
	uint8 const _commandClassId,
	uint8 const _instance,
	uint8 const _index,
	string const& _label,
	string const& _units,
	bool const _readOnly,
	vector<Item> const& _items,
	int32 const _valueIdx
):
	Value( _homeId, _nodeId, _genre, _commandClassId, _instance, _index, ValueID::ValueType_List, _label, _units, _readOnly, false ),
	m_items( _items ),
	m_valueIdx( _valueIdx )
{
}

//-----------------------------------------------------------------------------
// <ValueList::ReadXML>
// Apply settings from XML
//-----------------------------------------------------------------------------
void ValueList::ReadXML
(
	uint32 const _homeId,
	uint8 const _nodeId,
	uint8 const _commandClassId,
	TiXmlElement const* _valueElement
)
{
	Value::ReadXML( _homeId, _nodeId, _commandClassId, _valueElement );

	// Read the items
	m_items.clear();
	TiXmlElement const* itemElement = _valueElement->FirstChildElement();
	while( itemElement )
	{
		char const* str = itemElement->Value();
		if( str && !strcmp( str, "Item" ) )
		{
			char const* labelStr = itemElement->Attribute( "label" );

			int value = 0;
			itemElement->QueryIntAttribute( "value", &value );

			Item item;
			item.m_label = labelStr;
			item.m_value = value;

			m_items.push_back( item );
		}

		itemElement = itemElement->NextSiblingElement();
	}

	// Set the value
	int intVal;
	m_valueIdx = 0;
	if ( TIXML_SUCCESS == _valueElement->QueryIntAttribute( "value", &intVal ) )
	{
		m_valueIdx = (int32)intVal;
		SetIsSet();
	}
}

//-----------------------------------------------------------------------------
// <ValueList::WriteXML>
// Write ourselves to an XML document
//-----------------------------------------------------------------------------
void ValueList::WriteXML
(
	TiXmlElement* _valueElement
)
{
	Value::WriteXML( _valueElement );
	
	char str[16] = "";
	if ( IsSet() )
    {
		snprintf( str, 16, "%d", m_valueIdx );
    }
	_valueElement->SetAttribute( "value", str );

	for( vector<Item>::iterator it = m_items.begin(); it != m_items.end(); ++it )
	{
		TiXmlElement* pItemElement = new TiXmlElement( "Item" );
		pItemElement->SetAttribute( "label", (*it).m_label.c_str() );
		
		snprintf( str, 16, "%d", (*it).m_value );
		pItemElement->SetAttribute( "value", str );

		_valueElement->LinkEndChild( pItemElement );
	}
}

//-----------------------------------------------------------------------------
// <ValueList::SetByLabel>
// Set a new value in the device, selected by item label
//-----------------------------------------------------------------------------
bool ValueList::SetByLabel
(
	string const& _label
)
{
	// Ensure the value is one of the options
	int32 index = GetItemIdxByLabel( _label );
	if( index < 0 )
	{
		// Item not found
		return false;
	}

	// Set the value in our records.
	OnValueChanged( m_items[index].m_value );

	// Set the value in the device.
	return Value::Set();
}

//-----------------------------------------------------------------------------
// <ValueList::SetByValue>
// Set a new value in the device, selected by item value
//-----------------------------------------------------------------------------
bool ValueList::SetByValue
(
	int32 const _value
)
{
	// Set the value in our records.
	OnValueChanged( _value );

	// Set the value in the device.
	return Value::Set();
}

//-----------------------------------------------------------------------------
// <ValueList::OnValueChanged>
// A value in a device has changed
//-----------------------------------------------------------------------------
void ValueList::OnValueChanged
(
	int32 const _value
)
{
	// Ensure the value is one of the options
	int32 index = GetItemIdxByValue( _value );
	if( index < 0 )
	{
		// Item not found
		return;
	}

	m_valueIdx = index;
	Value::OnValueChanged();
}

//-----------------------------------------------------------------------------
// <ValueList::GetItemIdxByLabel>
// Get the index of an item from its label
//-----------------------------------------------------------------------------
int32 const ValueList::GetItemIdxByLabel
(
	string const& _label
)
{
	for( int32 i=0; i<(int32)m_items.size(); ++i )
	{
		if( _label == m_items[i].m_label )
		{
			return i;
		}
	}

	return -1;
}

//-----------------------------------------------------------------------------
// <ValueList::GetItemIdxByValue>
// Get the index of an item from its value
//-----------------------------------------------------------------------------
int32 const ValueList::GetItemIdxByValue
(
	int32 const _value
)
{
	for( int32 i=0; i<(int32)m_items.size(); ++i )
	{
		if( _value == m_items[i].m_value )
		{
			return i;
		}
	}

	return -1;
}

//-----------------------------------------------------------------------------
// <ValueList::GetItemLabels>
// Fill a vector with the item labels
//-----------------------------------------------------------------------------
bool ValueList::GetItemLabels
(
	vector<string>* o_items
)
{
	if( o_items )
	{
		for( vector<Item>::iterator it = m_items.begin(); it != m_items.end(); ++it )
		{
			o_items->push_back( (*it).m_label );
		}

		return true;
	}

	return false;
}







