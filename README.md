### Game of Life
Read this wiki article to know more about [The Game Of Life](https://en.wikipedia.org/wiki/Conway%27s_Game_of_Life)

## Challenge Documentation
[Documentation](https://github.com/careernowbrands/full-stack-engineer/blob/master/challenges/coding-challenge-1.md)


### How to install this project
```
# install laravel
git clone https://github.com/phpcodebooster/gameoflife.git
cd gameoflife
composer install

# run this game command line in your terminal
php artisan game:start
```

### Additional parameters
- Above command can take two initial arguments width and height of the terminal
- Following is the example with arguments

```
php artisan game:start --w=100 --h=40
```