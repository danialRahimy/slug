# meta-laravel

## Installation
This project using composer.
```
$ composer require danialrahimy/meta-laravel
```

## Usage

### Do this steps

By default, config file path is 
`/resources/etc/sourcesHtml.json`

So like this format generate config file
```json
{
    "sassToCss": {
        "client": [
            "resources/sass/client/styles.scss"
        ],
        "shared": [],
        "admin": []
    },
    "minify": {
        "client": {
            "home": {
                "js": [
                    "resources/vendor/jquery/v3.3.1/js/jquery.min.js",
                    "resources/vendor/popper/v2019/js/popper.min.js",
                    "resources/vendor/bootstrap/v4.3.1/js/bootstrap.min.js",
                    "resources/vendor/jqueryEasing/v1.3/js/jquery.easing.min.js",
                    "resources/vendor/swiper/v4.4.6/js/swiper.min.js",
                    "resources/vendor/magnific/v1.1.0/js/jquery.magnific-popup.js",
                    "resources/vendor/validator/v0.11.8/js/validator.min.js",
                    "resources/js/client/scripts.js"
                ],
                "css": [
                    "resources/vendor/bootstrap/v4.3.1/css/bootstrap.css",
                    "resources/vendor/fontawesome/v5.0.13/css/fontawesome-all.css",
                    "resources/vendor/swiper/v4.4.6/css/swiper.css",
                    "resources/vendor/magnific/v1.1.0/css/magnific-popup.css",
                    "resources/css/client/styles.css"
                ]
            }
        },
        "admin": {},
        "shared": {}
    }
}
```

Add this codes or replace ( depends on you ) to `webpack.mix.js` in root of project
```js
const mix = require('laravel-mix');
const fs = require('fs');
const env = require('dotenv');

const config = env.config({path: '.env'})["parsed"];
let data = fs.readFileSync("resources/etc/sourcesHtml.json", 'utf8');
data = JSON.parse(data);

if (!data.hasOwnProperty("minify")){

    console.error("Config File {sourcesHtml.json} must be has minify key");
    return;
}

if (data.hasOwnProperty("sassToCss")){

    let i, j;

    for (i in data["sassToCss"]){

        for (j in data["sassToCss"][i]){

            mix.sass(data["sassToCss"][i][j], `public/css/${i}`)
        }
    }
}

if (data.hasOwnProperty("minify")){

    let type, id, category;
    let list = {}

    for (category in data["minify"]){

        for (id in data["minify"][category]){

            for (type in data["minify"][category][id]){

                if (!list.hasOwnProperty(type))
                    list[type] = {};

                if (!list[type].hasOwnProperty(category))
                    list[type][category] = {};

                list[type][category][id] = data["minify"][category][id][type]
            }
        }
    }

    for (type in list){

        for (category in list[type]){

            for (id in list[type][category]){

                if (type === "js")
                    mix.scripts(list[type][category][id], `public/${type}/${category}/${id}.js`);

                if (type === "css")
                    mix.styles(list[type][category][id], `public/${type}/${category}/${id}.css`);
            }
        }
    }
}
```

To continue create a new key in .env file in root of your project `VERSION=dev` also can set prod when your project in production mode

### Let's use

To describe config file there are two main key:
1. sassToCss
    * in this object can define multi key that they are array that contain sass files path compile to css files,
    according to keys, for example client, sass file that are in this array
    compiled file put in `/public/css/client` directory
    
2. minify
    * in this object can define multi key ( they can be your main category ) that they are object in their object 
    can be multi key ( they can be your subcategory ), and they are also object contains to key: 1.js 2.css that they
    are array keep your css and js files path.
    * when use `VERSION=dev` css and js file load in your pages like the list ypu define in config file
    * when use `VERSION=prod` if you load client category and home subcategory css and js file path in page
    is `/css/client/home.css`, `/js/client/home.js`
    
>>> To hint, if your project is a website you can use main categories to separate application to parts (admin, client)
> and use sub category to separate each page

### Use in blade files

get js file

client category, home subcategory
```blade
    <?= \Danialrahimy\MetaLaravel\Meta::getJs("client", "home") ?>
```

get css file

client category, home subcategory
```blade
    <?= \Danialrahimy\MetaLaravel\Meta::getCss("client", "home") ?>
```

get css and js together, in order css first 

client category, home subcategory
```blade
    <?= \Danialrahimy\MetaLaravel\Meta::get("client", "home") ?>
```