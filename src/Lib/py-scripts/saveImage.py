import math
import random
import time
import urllib.request


def uniqid(prefix='', more_entropy=False):
    m = time.time()
    sec = math.floor(m)
    usec = math.floor(1000000 * (m - sec))
    if more_entropy:
        lcg = random.random()
        the_uniqid = "%08x%05x%.8F" % (sec, usec, lcg * 10)
    else:
        the_uniqid = '%8x%05x' % (sec, usec)

    the_uniqid = prefix + the_uniqid
    return the_uniqid


def saveMainImage(url, path):
    start_path = 'C:/Users/mrart/server/domains/eshop.bx/public/img/'
    imageName = uniqid()
    fullPath = start_path + path + imageName + '.webp'
    urllib.request.urlretrieve(url, fullPath)
    return imageName
