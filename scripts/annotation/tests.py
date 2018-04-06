from dbUtils import *
from DishinUtils import *
from MERUtils import *
from calcUtils import *
from constants import *

#program entry point
try:
    result = subprocess.run(["python3", DISHIN_py_path, DISHIN_DB_path, 'DOID_9970', 'DOID_2938'], \
                            cwd=DISHIN_path, stdout=subprocess.PIPE)
except:
    print('oops')
