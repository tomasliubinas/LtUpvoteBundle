# LtUpvoteBundle
LtUpvoteBundle is upvote and downvote (thumbs up and thumbs down) component 
for Symfony 3.0 project

## Features 
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

* Add the line in the app/AppKernel.php file:

 ```php
 $bundles[] = new Lt\UpvoteBundle\LtUpvoteBundle();
 ```

* Create required database tables:

 ```
 $ bin/console  doctrine:schema:update
 ```

## Configuration

The following is an example configuration defining basic context types `blog-post` and
 `comment`:

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

Test page could be accessed by navigating to 
`http://<dev-host>/lt-upvote-test` on the `dev` environment. 

## Front-end

Front-end functionality is implemented in a single dependency free JavaScript file.
Styles are defined in CSS file which could be used out of the box 
or adopted according to custom requirements.

### JavaScript

* Include JavaScrip module file (Twig syntax):

 ```html
 <script src="{{asset('/bundles/ltupvote/js/lt-upvote.js')}}"></script>
 ```

* Initialize JavaScript module in your HTML page:

 ```html
 <script language="JavaScript"><!--
     ltupvote.init();
     //-->
 </script>
 ```

### CSS

* Include basic CSS file in your html `<head>` section:

 ```html
 <link rel="stylesheet" type="text/css" href="{{ asset('/bundles/ltupvote/css/lt-upvote.css') }}">
```

### Twig 

* Include one or more front end components into your page rendered over Controller
```
{{ render(
    controller(
        'lt_upvote_bundle.controller.default:renderVoteComponent',
        {
            'subjectType': '[TYPE]',
            'subjectId': '[ID]',
            'css_class': '[CLASS]' 
        }
    )
) }}
```

Where:
 * `[TYPE]` is context type value.
 * `[ID]` is Subject ID value.
 * `[CLASS]` is component specific styling CSS class. Predefined styles are `Style1`, `Style2`. 

See [test.html.twig](Resources/views/Default/test.html.twig) file for example implementation.

### JavaScript Custom Event handling

On each upvote/downvote action JavaScript event is dispatched.
This action could be handled by adding custom event listener for `ltu` event type.

An example code for anonymous downvote handling: 
 
```JavaScript
addEventListener('ltu', function(event) {
    if (event.detail.unauthorizedError) {
        alert('This action is permitted for the logged in visitors only.');
    }
})
```   
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
The following properties describing performed action could be accessed from `even.detail` data object: `id`, `type`, `counter`, `action`, `unauthorized`, `unauthorizedError`. 

## License

This bundle is under the MIT license. See the complete license in [LICENSE](LICENSE) file.
