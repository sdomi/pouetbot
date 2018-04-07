# pouetbot
A simple bot reposting random prods from [pouet.net](http://pouet.net). Coded from scratch for fun, live on [botsin.space](https://botsin.space/@pouetnet)!

## Running & Configuration
You'll need php5 or php7, and either php5-curl or php-curl.
Configuration boils down to changing data.json file, of which you can get a sample below.

'{
	"instance":"botsin.space",
	"token":"<insert your token here>",
	"idStart":1,
	"idEnd":75844
}'

idStart and idEnd are the first and the last prod id respectively. idEnd will be updated automagically at every run, but if it's your first run - it may send a ton of API requests. To prevent this, go to the main pouet.net page and get the last prod id from the sidebar on the left.

Have fun!!