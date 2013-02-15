#!/usr/bin/env python
# -*- coding: utf-8 -*-

import time
import datetime
import sys
import os
import glob
from common.ozwWrapper import ZWaveWrapper, ZWaveNode, ZWaveValueNode
from decimal import *
import subprocess
# ================================================================================================

device_path = sys.argv[1]


def php(script_path):
    p = subprocess.Popen(['php', script_path], stdout=subprocess.PIPE)
    result = p.communicate()[0]
    return result

def initialization(device_path):
	getcontext().prec = 3
	executionTime = Decimal(0)
	maxExecutionTime = Decimal(60) # Maximum initialization time (in seconds)
	sleepTime = Decimal(0.1)

	w = ZWaveWrapper.getInstance(device = device_path, config = '../py-openzwave/openzwave/config/')

	feedback = "OK<br>"

	while bool(w.initialized) != True:
		executionTime = executionTime + Decimal(sleepTime)
		time.sleep(sleepTime)
		if executionTime > maxExecutionTime:
			seq = ['INITIALIZATION FAILED | Execution time: ', executionTime, ' seconds']
			feedback = ''.join(map(str, seq))
			break


	return feedback




while bool(True): 
	# Remove zwcfg_0x014d0d27.xml
	#news_script_output = php("server.php")
	#print initialization(device_path)
	print device_path
	# print news_script_output
	# sys.stdout.write("*")
	sys.stdout.flush()
	time.sleep(1)
	# time.sleep in seconds