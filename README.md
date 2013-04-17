ModifyRequestHeadersBundle
=============

This bundle allows to add arbitrary request headers to the Request object.

The use case that forced me to develop it, was because my application is behind a reverse proxy, wich is not setting the headers x-forwarded-proto. Thus, links sended by mail had an incorrect protocol.

Features include:

- Set the custom headers
- Set the priority we want the listener to run when the kernel event.

Installation
------------

1. Download EpilgrimModifyRequestHeadersBundle using composer
2. Enable the Bundle
3. Configure your application's config.yml

### Step 1: Download EpilgrimModifyRequestHeadersBundle using composer
Add EpilgrimModifyRequestHeadersBundle in your composer.json:

```js
{
    "require": {
        "epilgrim/modify-request-headers-bundle": "*"
    }
}
```

And then run

``` bash
$ php composer.phar update
```

### Step 2: Enable the bundle
Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Epilgrim\ModifyRequestHeadersBundle\EpilgrimModifyRequestHeadersBundle(),
    );
}
```

3. Configure your application's config.yml

``` yaml
# app/config/config.yml
epilgrim_modify_request_headers:
    headers:
        - {name: x_forwarded_proto, value: https}
        - {name: header2, value: value_2}
    listener_priority: 64
```
You can add as many headers as you want.


Notes
-------

**It's important to note that the listener_priority must be set higher than the subsequent listener making use of the headers.**
For example, if you modify the x_forwarded_proto, it is used by the RouterListener (running with priority 32). Thus, you must set something higher. It defaults to 64.
If the key epilgrim_modify_request_headers is not added to app/config/config.yml, then the listener won´t be registered.

License
-------

This bundle is under the MIT license.

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/Epilgrim/ModifyRequestHeadersBundle/issues).
