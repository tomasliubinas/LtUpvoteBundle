# LtUpvoteBundle
LtUpvoteBundle is upvote and downvote (thumbs up and thumbs down) component 
for Symfony 3.0 project

#### Features 
- Configurable anonymous vote permissions
- Automatically limit anonymous upvotes/downvotes by IP
- Optionally hide upvote/downvote button
- Pure JavaScript frontend component implementation
- Unit tested

## System requirements

- Symfony 3.0
- Doctrine bundle with configured database connection


## Installation

* LtUpvoteBundle could be installed over Composer:

 ```
 composer require liubinas/upvote-bundle
 ```

* Enable the bundle by adding the following line in the app/AppKernel.php file:

 ```php
 $bundles[] = new Lt\UpvoteBundle\LtUpvoteBundle();
 ```

* Create required database tables:

 ```
 $ bin/console  doctrine:schema:update
 ```

## Configuration

The following is an example yml configuration defining 2 basic context types `blog-post` and
 `comment` to be used by LtUpvoteBundle:

```yml
    # app/config/config.yml
    lt-upvote-bundle:
        types:
            blog-post: # Custom context type
                show_upvote: true
                show_downvote: true
                allow_anonymous_upvote: true
                allow_anonymous_downvote: false
            comment # Custom context type having the default values all true

```


## Test run

Bundle test page could be accessed by navigating to 
`http://<dev-host>/lt-upvote-test` on the `dev` environment. 

## Front-end

Front-end part of the bundle is implemented in a single dependency free JavaScript file.
It also requires basic element styling defined in a CSS file which could be used as it is 
or adopted according to custom requirements.

### Basic usage 

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

* Initialize JavaScript module in your HTML page:

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

### JavaScript Custom Event handling

On each upvote/downvote action JavaScript event is dispatched.
This action could be handled by adding custom event listener for `ltu` event type.

The following example shows anonymous downvote handling: 

```JavaScript
addEventListener('ltu', function(event) {
    if (event.detail.unauthorizedError) {
        alert('This action is permitted for the logged in visitors only.');
    }
})
```   

## License

This bundle is under the MIT license. See the complete license in [LICENSE](LICENSE) file.
