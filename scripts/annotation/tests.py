from dbUtils import *
from DishinUtils import *
from MERUtils import *
from calcUtils import *
from constants import *
from decimal import *

#program entry point
minDate = datetime(2017, 1, 1, 0, 0, 0)
maxDate = datetime.now()
maxDate = maxDate.replace(hour=0, minute=0, second=0, microsecond=0)

value = datetime(2018, 3, 12, 0, 0, 0)

print((maxDate - minDate).days)
print(rescaleDatetime(value, minDate, maxDate))

print(Decimal(2) * 3)
