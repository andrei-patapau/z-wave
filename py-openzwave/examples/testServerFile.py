#!/usr/bin/env python
# -*- coding: utf-8 -*-

import time
import datetime
import sys
import os
import glob
from common.ozwWrapper import ZWaveWrapper, ZWaveNode, ZWaveValueNode
from decimal import *
# ================================================================================================
device_path = sys.argv[1]

def prfeed(device_path):
	data = []
	getcontext().prec = 3
	executionTime = Decimal(0)
	maxExecutionTime = Decimal(75) # Maximum initialization time (in seconds)
	sleepTime = Decimal(0.1)

	w = ZWaveWrapper.getInstance(device = device_path, config = '../openzwave/config/')

	feedback = "SUCCESS"
	# print sys.path
	#print "<br><br>Initializing devices, please wait ...<br>"
	while bool(w.initialized) != True:
		executionTime = executionTime + Decimal(sleepTime)
		time.sleep(sleepTime)
		if executionTime > maxExecutionTime:
			seq = ['INITIALIZATION FAILED | Execution time: ', executionTime, ' seconds']
			feedback = ''.join(map(str, seq))
			break
	
	if feedback == "SUCCESS":
		ln = w.nodes
		for n in ln:
			#data.append("<"); data.append("Node ID: "); data.append(w.nodes[n].nodeId); data.append(">");
			#data.append("<"); data.append("Is ON: "); data.append(w.nodes[n].isOn); data.append(">");
			#data.append("<"); data.append("Level: "); data.append(w.nodes[n].level); data.append(">");
			#data.append("<"); data.append("Name: "); data.append(w.nodes[n].name); data.append(">");
			#data.append("<"); data.append("Product Type: "); data.append(w.nodes[n].productType); data.append(">");
			#data.append("<"); data.append("Product: "); data.append(w.nodes[n].product); data.append(">");
			
			dataList = [w.nodes[n].nodeId]; dataList = ''.join(map(str, dataList)); data.append(dataList);
			dataList = [w.nodes[n].isOn]; dataList = ''.join(map(str, dataList)); data.append(dataList);
			dataList = [w.nodes[n].level]; dataList = ''.join(map(str, dataList)); data.append(dataList);

		return data
	else:
		return feedback



#print feedback
print prfeed(device_path)
#data = array(data)

#print "<br>Something else"
# sys.stdout.write("*")
# sys.stdout.flush()
# Initialize adapter and ZWave devices and return TRUE(SUCCES) or FALSE(failed)
#gc.collect()
	
 
'''
sys.stdout.write(" DONE !<br>")

print "List of nodes :<br>"
ln = w.nodes

# print ln
for n in ln:
	lu = datetime.datetime.fromtimestamp(w.nodes[n].lastUpdate)
	print "================================================================================<br>"
	print "homeId: ", w.nodes[n].homeId, "<br>"
	print "nodeId: ", w.nodes[n].nodeId, "<br>"
	print "Product: ", w.nodes[n].product, "<br>"
	print "Manufacturer: ", w.nodes[n].manufacturer, "<br>"
	# print "node: ", w.nodes[n].id, "<br>"
	# print "Type: ", w.nodes[n].productType, "<br>"
	print "ProductType: ", w.nodes[n].productType, "<br>"
	print "Name: ", w.nodes[n].name, "<br>"
	print "Location: ", w.nodes[n].location, "<br>"
	print "isOn: ", w.nodes[n].isOn, "<br>"
	print "level: ", w.nodes[n].level, "<br>"
	print "Last update: ", lu.ctime(), "<br>"

	# print "groups: ", w.nodes[n].groups, "<br>"
	# print "isSleeping: ", w.nodes[n].isSleeping, "<br>"
	# print "isLocked: ", w.nodes[n].isLocked, "<br>"
	# print "batteryLevel: ", w.nodes[n].batteryLevel, "<br>"
	# print "signalStrength: ", w.nodes[n].signalStrength, "<br>"
	# print "capabilities: ", w.nodes[n].capabilities, "<br>"
	# print "neighbors: ", w.nodes[n].neighbors, "<br>"
	# print "commandClasses: ", w.nodes[n].commandClasses, "<br>"


	
	#if w.nodes[n].productType != 'Static PC Controller':
	#	for val in w.nodes[n].values:
	#		print "    ", w.nodes[n].values[val].valueData['label'], ' -- ', w.nodes[n].values[val].valueData['value'], ' -- ', w.nodes[n].values[val].valueData['units'], "<br>" 
	

print "<br><br>End of script<br><br>"
'''