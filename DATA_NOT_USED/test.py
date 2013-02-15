# bilingual.py
"""

Use str(object) to convert an object to a string. For example:
>>> str(42)
'42'

The reverse function is int(x) to convert a string to an integer:
>>> int('42')
42

"""
# FUNCTIONS AND DEFINITIONS ========================================
import sys

# functions
def concat(strng):
	global strArray					#define a global variable to access it(sort of a pointer)
	strArray.append(str(strng))		#convert strng object to string AND append it to array strArray


# variable declarations
strArray = []
string = ''

# END OF FUNCTIONS AND DEFINITIONS ==================================

if (len(sys.argv) > 1):
    if( sys.argv[1] == 'english' ):
        concat("Hello World!")
    elif( sys.argv[1] == 'german' ):
        concat("Guten Tag Welt!")
    else:
        concat("Which language?")
        concat("Welche sprache?")
else:
    concat("Which language do you want?")
    concat("Welch sprache wisst du?")

concat('<br><br>')
	
a = ['Mary','had','a','little','lamb'];
for i in range(len(a)):
	concat(i), concat(a[i])
	concat('<br>')
	

for i in range(20):
	concat(i)
	concat('all done?')



# JOIN STRINGS ========================================================
string = ''.join(strArray)
print string	
	
	
	
	
	

