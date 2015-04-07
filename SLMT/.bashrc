# .bashrc

# User specific aliases and functions

alias rm='rm -i'
alias cp='cp -i'
alias mv='mv -i'
alias ll='ls -alh'

# Source global definitions
if [ -f /etc/bashrc ]; then
	. /etc/bashrc
fi

umask 022
alias vi=vim
export PS1="\u@\H [\W]# "
export PROMPT_COMMAND='printf "\033]0;%s@%s:%s\007" "${USER}" ${HOSTNAME}' 
