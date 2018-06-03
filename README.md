# 1. Laravel Form Processor
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

This is a library that was inspired by one of the Laravel project i worked on that required me to run over half a dozen different updates on one particular `Model` or `Entity`. I could have easily created a different controller method and route for each request but it made my `web.php` file bloated and so was my `controller` and am a big fan of keeping my routes file as thin as possible and my controllers looking the same as it was when i first ran the `artisan make:controller` command, containing nothing else but the `index, store, show, edit, update and destroy` methods. So i decided to look for a way to process different but similar (maybe its to update the same model or sending the request to the same controller) form requests from one controller method and `Laravel Form Processor` was what i could come up with.

> **_Note: There might be another or other ways more intuitive to achieve this same results but at the time and giving my skill level in PHP and Laravel this looked like a really good idea to me, it's just my opinion so feel free to consult a pro._**

**This is a package for Laravel 5.x and Lumen 5.x**

[![Laravel 5](http://sammyk.s3.amazonaws.com/open-source/laravel-facebook-sdk/laravel-5.png)](http://laravel.com/docs)

[![Lumen 5](http://sammyk.s3.amazonaws.com/open-source/laravel-facebook-sdk/lumen-5.png)](https://lumen.laravel.com/docs)

<!-- TOC -->

- [1. Laravel Form Processor](#1-laravel-form-processor)
    - [1.1. Setting up](#11-setting-up)
        - [1.1.1. Installation on Lumen 5.x and Laravel 5.x.](#111-installation-on-lumen-5x-and-laravel-5x)
        - [1.1.2. Installation on Lumen and Laravel 5.4 and below.](#112-installation-on-lumen-and-laravel-54-and-below)
            - [1.1.2.1. Service Provider](#1121-service-provider)
            - [1.1.2.2. Facade (optional)](#1122-facade-optional)
        - [1.1.3. Publishing config file.](#113-publishing-config-file)
        - [1.1.4. Configure paths for generated processes](#114-configure-paths-for-generated-processes)
    - [1.2. Usage](#12-usage)
        - [1.2.1. Creating a form Process class](#121-creating-a-form-process-class)
        - [1.2.2. Setting up the controller to receive our process.](#122-setting-up-the-controller-to-receive-our-process)
            - [1.2.2.1. Using LaravelFormProcessorInterface](#1221-using-laravelformprocessorinterface)
            - [1.2.2.2. Using LaravelFormProcessorFacade](#1222-using-laravelformprocessorfacade)
    - [1.3. Security Vulnerabilities](#13-security-vulnerabilities)
    - [1.4. License](#14-license)

<!-- /TOC -->

## 1.1. Setting up
### 1.1.1. Installation on Lumen 5.x and Laravel 5.x.
Add the Laravel Form Processor package to your `composer.json` file.

```
composer require ac-developers/laravel-form-processor
```

> **Auto-discovery:** Is supported in Laravel Form Processor [auto-discovery](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518) for Laravel 5.5 and greater.

### 1.1.2. Installation on Lumen and Laravel 5.4 and below.
#### 1.1.2.1. Service Provider

In your app config, add the `LaravelFormProcessorServiceProvider` to the providers array.

```php
'providers' => [
    AcDevelopers\LaravelFormProcessor\LaravelFormProcessorServiceProvider::class,
    ];
```

For **Lumen**, add the provider to your `bootstrap/app.php` file.

```php
$app->register(AcDevelopers\LaravelFormProcessor\LaravelFormProcessorServiceProvider::class);
```

#### 1.1.2.2. Facade (optional)

If you want to make use of the facade, add it to the aliases array in your app config.

```php
'aliases' => [
    'LaravelFormProcessorFacade' => AcDevelopers\LaravelFormProcessor\LaravelFormProcessorFacade::class,
    ];
```
### 1.1.3. Publishing config file.
To publish the config file to `config/laravel-form-processor.php` run:

```
php artisan vendor:publish --provider="AcDevelopers\LaravelFormProcessor\LaravelFormProcessorServiceProvider"
```

### 1.1.4. Configure paths for generated processes
To change the paths of saving the generated processes, you need to configure their namespaces in a configuration file `config/laravel-form-processor.php`.

```

return [
    /*
    |--------------------------------------------------------------------------
    | Default namespaces for the classes
    |--------------------------------------------------------------------------
    */
    'namespaces' => [
        'process'   => 'App\Processes',
        'model'        => 'App\\',
    ],
];
```

And you�re good to go.

## 1.2. Usage

### 1.2.1. Creating a form Process class
Let's assume you have a model in your Laravel app called `Article` and you wish to run an update on it to change its `published field` from `false` to `true`, after configuring the directory you wish to place you�re Processes in, run:
```
php artisan generate:process PublishArticleProcess --model=Article
```
this will create a form process class tied to a `Model` in this case `Article` (of course specifying a model is optional).
If the process directory path is left as it is your processes will be stored in a `App\Processes` directory. Don�t worry, if this doesn't exist one will be created for you.

```
class PublishArticleProcess extends LaravelFormProcess implements LaravelFormProcessableInterface
{
    /**
     * PublishArticleProcess constructor.
     * @param \Illuminate\Http\Request $request
     * @param \App\Article $article
     */
    public function __construct(Request $request, Article $article)
    {
        $this->request = $request;
        $this->model = $article;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function handle()
    {
        //
    }
}
```
After the Process class has been generated, all the logic that is required to update the `Article published field` will go into the `handle` method.

> **_Tip! You�re processes should as much as possible look and respond like the controller methods they are been used in. e.g compare the constructor of the process generated above with the `update` method on a resourceful `ArticleController`._**

```
class PublishArticleProcess extends LaravelFormProcess implements LaravelFormProcessableInterface
{
    protected $request;

    protected $article;

    /**
     * PublishArticleProcess constructor.
     * @param \Illuminate\Http\Request $request
     * @param \App\Article $article
     */
    public function __construct(Request $request, Article $article)
    {
        $this->request = $request;
        $this->article = $article;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function handle()
    {
        // Perform validation using Laravel's ValidatesRequests trait
        $this->validate($this->request, ['published' => 'required']);

        // Update the model
        $result = $this->article->update($this->request->all());

        // Return to any desired location if update was successful. 
        if ($result) {
            return redirect()->route('articles.show', ['id' => $this->article->id])
                ->with('status', 'Article was updated successfully.');
        }

        // Return back if not.
        return back()->withErrors(['Unable to update this article.']);
    }
}
```
Although this is a simple use case, a lot of things can be done here like dispatching jobs, firing events, authorizations and so on, thanks to the use of Laravel's `AuthorizesRequests, DispatchesJobs, ValidatesRequests` extended by the abstract `LaravelFormProcess` class you can work in a process like you would in a Laravel controller. The important thing to take away from this is that all `Process handle` methods MUST only return a `Response` and not a `View`.

### 1.2.2. Setting up the controller to receive our process.
When it comes to where to put your process in a controller it only comes down to what category your actions fall into in the `CRUD` classification.
In our case this would be the `U` in `CRUD` which stands for `update`.

Now a lot of you would be asking "why else would you need to place a `Processor` in any other controller method aside from the `update` method"? 

Well imaging you have two delete buttons in your `view`, one for the admins and the other for the superuser, now when an admin decides to delete an article you as a superuser would want to have a finall say in the matter. So when processing a delete action for an admin a `soft delete` will be in order, followed by a notification letting the superuser know about the admin's intention to delete an article, now when the superuser decide to go along with their decision he/she can now perform a delete action that would permanently remove the article from the database.

#### 1.2.2.1. Using LaravelFormProcessorInterface
Now in your `update` method inside of you�re `ArticleController` class you would inject the `LaravelFormProcessorInterface` like this:
```
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article $article
     * @param LaravelFormProcessorInterface $laravelFormProcessorInterface
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article, LaravelFormProcessorInterface $formProcessorInterface)
    {
        $process = $formProcessorInterface->retrieveProcessFromFormField($request->get('_prKey'))

        return $formProcessorInterface->run(new $process($request, $article));
    }
```

#### 1.2.2.2. Using LaravelFormProcessorFacade
You can use the `LaravelFormProcessorFacade` if you're more comfortable with Facades like the example shows below.

```
/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $process = LaravelFormProcessorFacade::retrieveProcessFromFormField($request->get('_prKey'))

        return LaravelFormProcessorFacade::run(new $process($request, $article));
    }
```

and then in the form you wish to process you will use a `@renderProcess` directive provided with the package to point to the Process class that will be used to run or handle the form request.

```
<form action="route('articles.update', ['id' => article->id])" method="POST">
    @csrf
    @method('PATCH')}}

    @renderProcess(\App\Process\ArticlePublishProcess)
    
    <div class="d-block my-3">
        <div class="custom-control custom-radio">
            <input name="published" checked="" type="radio" value="true"><label>Publish</label>
        </div>
        <div class="custom-control custom-radio">
            <input name="published" type="radio" value="false"><label>Unpublish</label>
        </div>
    </div>
</form>
```
> The `renderProcess` method available on the `LaravelFormProcessorFacade` will achieve the same goals as the `@renderProcess` directive so feel free to use any of them as you wish.


And you�re done, now unless you have an issue with your code everything should run just fine.

## 1.3. Security Vulnerabilities
If you discover a security vulnerability within Laravel Form Processor, please send an e-mail to Anitche Chisom via anitchec.dev@gmail.com. All security vulnerabilities will be promptly addressed.

## 1.4. License
The Laravel Form Processor is open-sourced software licensed under the [MIT](LICENSE) license.
