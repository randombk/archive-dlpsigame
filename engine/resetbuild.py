import time

copyrightText = "/*\n * (C) Copyright 2012 David J. W. Li\n * Project DLPSIGAME\n */"
fileName = "buildversion.php"

file = open(fileName,'w')
if file:
    file.truncate()
    file.write("<?\n")
    file.write(copyrightText)
    file.write("\n\n\n")
    file.write("//AUTO-GENERATED FILE - DO NOT EDIT\n")
    file.write("$GLOBALS['_GAME_BUILD']={0};\n".format(0))
    file.write("$GLOBALS['_GAME_BUILD_TIME']={0};\n".format(int(time.time())))
file.close()
