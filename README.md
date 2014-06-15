
Install
=======

Install the phar into to your bin directory:

```
curl -o ~/bin/mike https://raw.githubusercontent.com/holgerk/php-mike/master/build/mike.phar && chmod +x ~/bin/mike
```

...or using composer:

```
composer global require holgerk/mike:dev-master
```


Todos
=====

 - [x] format task list nicely
 - [x] generate and format help
 - [x] Support dependencies
 - [x] zsh autocomplete
 - [ ] zsh autocomplete for params
 - [ ] bash autocomplete


ZSH-Autocompletion
------------------
Add this to your .zshrc
```zsh
# Mike autocompletion
# ===================
# (http://jaredforsyth.com/blog/2010/may/30/easy-zsh-auto-completion/)
_mike() {
    reply=(`mike -T --no-color | awk 'NR > 0 { print $1 }' | tr "\\n" " "`)
}
compctl -K _mike mike
```
