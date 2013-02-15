#!/usr/bin/env python
# -*- coding: utf-8 -*-

import time
import datetime
import sys
import os
import glob
from common.ozwWrapper import ZWaveWrapper, ZWaveNode, ZWaveValueNode
from decimal import *
# -----------------------------------------------------------------------------------------------+
device_path = sys.argv[1]


def prfeed(device_path):
	getcontext().prec = 3
	executionTime = Decimal(0)
	maxExecutionTime = Decimal(75) # Maximum initialization time (in seconds)
	sleepTime = Decimal(0.1)
	# -----------------------------------------------------------------------------------------------+
	# REMOVE zwcfg_*.xml FILE - DONE IN PHP BEFORE CALLING THIS FILE								 |
	# -----------------------------------------------------------------------------------------------+
	w = ZWaveWrapper.getInstance(device = device_path, config = '../openzwave/config/')

	feedback = "INITIALIZATION SUCCESS"

	while bool(w.initialized) != True:
		executionTime = executionTime + Decimal(sleepTime)
		time.sleep(sleepTime)
		if executionTime > maxExecutionTime:
			seq = ['INITIALIZATION FAILED | Execution time: ', executionTime, ' seconds']
			feedback = ''.join(map(str, seq))
			break
			
	return feedback


print prfeed(device_path)
