# LtUpvoteBundle
Provides thumbs up and thumbs down functionality for Symfony 3.0 project

#### Features
- Multiple content type support (as in upvote blog post and separately upvote comments, 
you basically need to support different types of content for that).
- Allow/disable anonymous upvote or/and downvote
- Optionally show upvote button only
- Pure JavaScript frontend component implementation
- JavaScript events on authorized/unauthorized votes.
- Unit tested

## System requirements

- Symfony 3.0, 4.0
- Doctrine bundle
- Configured database connection


## Installation

* LtUpvoteBundle could be installed over Composer:

 ```
 composer require liubinas/upvote-bundle
 ```

* Include the following lines into your AppKernel.php class file:

 ```php
 $bundles[] = Lt\UpvoteBundle\LtUpvoteBundle();
 ```

* Create required database tables:

 ```
 $ bin/console  doctrine:schema:update
 ```

Start webserver and run the test page to test your installation:
 
    [http://<dev-host>/lt-upvote-test]


## Configuration

The following is an example yml configuration defining 2 basic content types `blog-post` and
 `comment` to be used by LtUpvoteBundle:

```yml
    # app/config/config.yml
    lt-upvote-bundle:
        types:
            blog-post: # Custom type
                allow_upvote: true
                allow_downvote: true
                allow_anonymous_upvote: true
                allow_anonymous_downvote: false
            comment # Custom type having the default values 

```

## Basic usage

Frontend voting component is implemented in a single dependency free JavaScript file.
It also requires basic element styling defined in a CSS file which could be used as it is 
or adopted according to custom requirements. 

For the following steps it is assumed you are using Twig template engine and 
Assetic asset management package. However the files could be included for different
packages in a similar fashion:

* Include JavaScrip module file:

 ```html
 <script src="{{asset('/bundles/ltupvote/js/lt-upvote.js')}}"></script>
 ```

* Include basic CSS file asset within your twig template html document `<head>` section:

 ```html
 <link rel="stylesheet" type="text/css" href="{{ asset('/bundles/ltupvote/css/lt-upvote.css') }}">
```

* Initialize JavaScript module:

 ```html
 <script language="JavaScript"><!--
     ltupvote.init();
     //-->
 </script>
 ```

* Include one or more front end components into your page. 
In order for the component to be properly initialized and displayed you would need to render
the component over the Controller:  

```
{{ render(
    controller(
        'lt_upvote_bundle.controller.default:renderVoteComponent',
        {
            'subjectType': 'TYPE',
            'subjectId': 'ID',
            'css_class': 'CLASS' 
        }
    )
) }}
```

Where:
 * `TYPE` is quoted string value of type of the contents for this component
 * `ID` is quoted string value representing Subject ID.
 * `CLASS` is is component specific styling CSS class. Predefined styles are `Style1` and `Style2`. 

See [test.html.twig](Resources/views/Default/test.html.twig) file for example implementation.

## JavaScript Custom Event handling

On each upvote/downvote action custom event is dispatched. The type of the event is `ltu`.
Visitor upvote/downvote action could be handled by adding custom event listener and checking event `detail` property if needed.
The following example shows anonymous downvote handling: 

```JavaScript
addEventListener('ltu', function(event) {
    var action = event.detail.action;
    var anonymous = event.detail.anonymous;
    if (anonymous && action === 'downvote') {
        alert('This action is permitted for the logged in visitors only.');
    }
})
```   

## License

This bundle is under the MIT license. See the complete license in the bundle.
