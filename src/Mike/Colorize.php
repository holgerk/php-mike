<?php

namespace Mike;

class Colorize {
    # Reset
    const Colorize_Off = '\033[0m';       # Text Reset

    # Regular Colorizes
    const Black = '\033[0;30m';        # Black
    const Red = '\033[0;31m';          # Red
    const Green = '\033[0;32m';        # Green
    const Yellow = '\033[0;33m';       # Yellow
    const Blue = '\033[0;34m';         # Blue
    const Purple = '\033[0;35m';       # Purple
    const Cyan = '\033[0;36m';         # Cyan
    const White = '\033[0;37m';        # White

    # Bold
    const BBlack = '\033[1;30m';       # Black
    const BRed = '\033[1;31m';         # Red
    const BGreen = '\033[1;32m';       # Green
    const BYellow = '\033[1;33m';      # Yellow
    const BBlue = '\033[1;34m';        # Blue
    const BPurple = '\033[1;35m';      # Purple
    const BCyan = '\033[1;36m';        # Cyan
    const BWhite = '\033[1;37m';       # White

    # Underline
    const UBlack = '\033[4;30m';       # Black
    const URed = '\033[4;31m';         # Red
    const UGreen = '\033[4;32m';       # Green
    const UYellow = '\033[4;33m';      # Yellow
    const UBlue = '\033[4;34m';        # Blue
    const UPurple = '\033[4;35m';      # Purple
    const UCyan = '\033[4;36m';        # Cyan
    const UWhite = '\033[4;37m';       # White

    # Background
    const On_Black = '\033[40m';       # Black
    const On_Red = '\033[41m';         # Red
    const On_Green = '\033[42m';       # Green
    const On_Yellow = '\033[43m';      # Yellow
    const On_Blue = '\033[44m';        # Blue
    const On_Purple = '\033[45m';      # Purple
    const On_Cyan = '\033[46m';        # Cyan
    const On_White = '\033[47m';       # White

    # High Intensity
    const IBlack = '\033[0;90m';       # Black
    const IRed = '\033[0;91m';         # Red
    const IGreen = '\033[0;92m';       # Green
    const IYellow = '\033[0;93m';      # Yellow
    const IBlue = '\033[0;94m';        # Blue
    const IPurple = '\033[0;95m';      # Purple
    const ICyan = '\033[0;96m';        # Cyan
    const IWhite = '\033[0;97m';       # White

    # Bold High Intensity
    const BIBlack = '\033[1;90m';      # Black
    const BIRed = '\033[1;91m';        # Red
    const BIGreen = '\033[1;92m';      # Green
    const BIYellow = '\033[1;93m';     # Yellow
    const BIBlue = '\033[1;94m';       # Blue
    const BIPurple = '\033[1;95m';     # Purple
    const BICyan = '\033[1;96m';       # Cyan
    const BIWhite = '\033[1;97m';      # White

    # High Intensity backgrounds
    const On_IBlack = '\033[0;100m';   # Black
    const On_IRed = '\033[0;101m';     # Red
    const On_IGreen = '\033[0;102m';   # Green
    const On_IYellow = '\033[0;103m';  # Yellow
    const On_IBlue = '\033[0;104m';    # Blue
    const On_IPurple = '\033[0;105m';  # Purple
    const On_ICyan = '\033[0;106m';    # Cyan
    const On_IWhite = '\033[0;107m';   # White
}
