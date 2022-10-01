import hashlib
from getpass import getpass

base10 = list("0123456789")
base26 = list("abcdefghijklmnopqrstuvwxyz")
base26 = list("abcdefghijklmnopqrstuvwxyz")
base36 = list("abcdefghijklmnopqrstuvwxyz0123456789")
base52 = list("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz")
base62 = list("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789")
base72 = list("ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz123456789#$%&()+-<=>?_^")

baseMap = {10: base10, 26: base26, 36: base36, 52: base52, 62: base62, 72: base72}
ITERATION = 65536

f = open(".key", "r")
key = f.readline()
f.close()


phrase = getpass("phrase: ")
hash = hashlib.pbkdf2_hmac("sha512", (key + phrase).encode(), "aikotoba".encode(), 65536).hex()

print(hash)

while True:
    print("config: ", end="")
    config = input()

    if config == "exit":
        break

    seed, baseStr, lengthStr = config.split(",")

    base = int(baseStr)
    length = int(lengthStr)
    baseArray = baseMap[base]

    rawpassword = int(hashlib.pbkdf2_hmac("sha512", phrase.encode(), seed.encode(), ITERATION).hex(), 16)

    print("-" * 10)
    for i in range(length):
        k = baseArray[rawpassword % base]
        rawpassword //= base
        print(k, end="")
    print()
    print("-" * 10)
