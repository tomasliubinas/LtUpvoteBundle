# LtUpvoteBundle
Provides thumbs up and thumbs down functionality for Symfony 3.0 project

#### Features
- Multiple content type support (as in upvote blog post and separately upvote comments, 
you basically need to support different types of content for that).
- Allow/disable anonymous upvote or/and downvote
- Optionally show upvote button only
- Vanilla JavaScript frontend component implementation
- JavaScript events on authorized/unauthorized votes.

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

Run Bundle test page in order to test your installation:
 
    [http://<dev-host>/lt-upvote-test]

Test page Controller is using isolated test configuration.
 

## Configuration

The following is an example yml configuration defining and enabling 2 content types `blog-post` and
 `comment` to be used by LtUpvoteBundle:

```yml
    # app/config/config.yml
    lt-upvote-bundle:
        types:
            # Example:
            - blog-post: # Custom type
                allow_upvote: true
                allow_downvote: true
                allow_anonymous_upvote: true
                allow_anonymous_downvote: false
            - comment

```

## Basic usage

To include Voting component in your project you need to:

* Include basic CSS file asset within your twig template html document `<head>` section:

 ```html
 <link rel="stylesheet" type="text/css" href="{{ asset('/bundles/ltupvote/css/lt-upvote.css') }}">
```

* Include JavaScrip module file:

 ```html
 <script src="{{asset('/bundles/ltupvote/js/lt-upvote.js')}}"></script>
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

See [test.html.twig] file for example implementation.

## Styling

Frontend Component files:
* Base JavaScipt module:
    `Resources/public/js/lt-upvote.js`   
* Base CSS file:
    `Resources/public/css/lt-upvote.css` 
* Twig template:
    `Resources/views/Default/upvote.html.twig`

The bundle comes with a couple of predefined sample style clasess which could be reused or adapted as required.

## JavaScript Event handling

...

## Support
Commercial support and custom adaptation are available from the fork owner. 


## License

This bundle is under the MIT license. See the complete license in the bundle.
