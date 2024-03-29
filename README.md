## Shorty
Shorty is a very small, self hosted, symfony application which provides
short links instead of long urls. This makes url sharing really simple.
So if you have a url like
https://www.example.com/this/is/a/very/long/url/you?will&not&remeber you
can give it to Shorty, and he will make an 8 character url from it. So
the long url becomes to something like this:
https://you-url.tld/9c6a8a7d

## Motivation
This kind of application is nothing new to the world. Services like
[bit.ly](https://bitly.com/) or [tinyurl](https://tinyurl.com/) do the
same thing in a more professional way. So why should I build this kind
of application by my self? There are several reasons for that, but the
main reason is: "Because I can!" Further I needed a application to play
with on google app engine. Furthermore its also a cool topic for a talk
and it's really cool to have your own service that does url shortening
for you.

## Wabi-Sabi
For everyone who's new to this concept plz. take a look at
[this article](https://en.wikipedia.org/wiki/Wabi-sabi). So what I want
to say is, I know this application is not perfect and there are many
things you can do in other or as you might think "better" ways. But this
is totally ok and I'm fine with that. So if there is something you would
like to change or optimize feel free to fork this repo and do it the way
you prefer.

## Setup
### Local
To get Shorty up'n running follow these simple steps:

- clone the repository
- run composer install`
- change the `PUT_TOKEN` inside the .env file to something you prefer
- change the `DELETE_TOKEN` inside the .env file to something you prefer
- run `php bin/console doctrine:database:create` to create the database
- run `php bin/console doctrine:migrations:migrate --no-interaction ` to setup the schema
- navigate to /public and run the local PHP Server with `php -S 127.0.0.1:8000`

You can now open `127.0.0.1:8000` in your favorite browser and use
Shorty.

### On a real Server
If you want to use Shorty on a real server just go through the local
setup steps on your machine except the local PHP server startup. You
will need the sqlite php extension to interact with the database. Because 
Shorty uses redirects the `symfony/apache-pack` is pre installed. So if 
you're using apache2 you're ready to go. For nginx you have to ensure 
that the redirects are configured correctly and that the `Authorization`
 header is passed. Further I recommend to enable HTTPS on your server.

## Usage
### First run
After you setup Shorty successfully you can navigate to your shorting
TLD (https://you-url.tld). Because there is nothing to do for Shorty, he
will provide you a 418 HTTP Response. This is totally normal and tells
you that Shorty is going out to have a cup of tea if there is some free time.

### Create a short link
If you want that Shorty creates a short link for you, you have to send
him a PUT request. The following example will show you how it works with
curl in the terminal.

```
curl -X PUT -H 'Authorization: 5449f071773861dca4d3b459aa79fcf5' -d 'https://github.com/patrick-blom/shorty' http://127.0.0.1:8000
```
```
-X PUT: Tell curl to send a PUT request
-H 'Authorization: 5449f071773861dca4d3b459aa79fcf5': The token you provide under .env PUT_TOKEN (5449f071773861dca4d3b459aa79fcf5)
-d 'https://github.com/patrick-blom/shorty': The Url you want to short (must be a syntactical valid url)
http://127.0.0.1:8000: Your running Shorty
``` 

If the request is valid Shorty will provide you a 201 Created HTTP
Response and an 8 characters long string which represents your link. In 
our example it's the string: 950a0065

If you provide a wrong authorisation token in the header or just
something that is no url, Shorty will provide you a 400 HTTP Bad Request
Response

### Use a short link
Based on the example above we can now use the generated identifier in a
expected way. So call http://127.0.0.1:8000/950a0065 in your browser.
Shorty will now take the identifier and search for it in his database.
If he finds a result he will pass a 301 HTTP Response including the 
original url back to the browser. 

### Delete a short link
Sometimes it happens that you pass something to Shorty you want to delete 
later. This can happen for several reasons. One reason could be that there is a typo
in your very long url and you don't want to mess up the database. Another one 
could be, that you only want to share the short link for a limited amount of time.
Never mind, there can always be a reason for the deletion of a short link. So if
you want to delete a short link you have to send Shorty a DELETE request with the
created short code. If we take the example from above, it would look like this.

```
curl -X DELETE -H 'Authorization: 5449f071773861dca4d3b459aa79fcf5' -d '950a0065' http://127.0.0.1:8000
```
```
-X DELETE: Tell curl to send a DELETE request
-H 'Authorization: 5449f071773861dca4d3b459aa79fcf5': The token you provide under .env DELETE_TOKEN (5449f071773861dca4d3b459aa79fcf5)
-d '950a0065': The short code which was created by Shorty during the PUT request
http://127.0.0.1:8000: Your running Shorty
``` 

If everything works as expected, Shorty will return a 410 HTTP Gone Response. 
Otherwise you will end up with an 400 HTTP Bad Request Response.

### Security
Shorty's defense is really simple. The only thing between the world and the database
are your authorization tokens. So please keep the following recommendations in mind

- choose long and complex tokens
- never share your tokens 
- never use the same token for PUT and DELETE 
- always use HTTPS, if you can

## Testing
### Regular Testing
If you want to test Shorty you can this by simply run a regular
`composer install` and `composer test` in the project root. This will
install all the dev dependencies and runs the test suite consisting of 
phpstan, phpcs, phpmd and phpunit.

You can also test only parts of the suite by calling the composer 
commands separately:

- composer phpstan  
- composer phpmd  
- composer phpcs  
- composer phpunit  

### Mutation Testing
To keep the quality of the tests high, Shorty uses mutation testing. 
The testing tool will be installed with composer dev dependencies. To 
execute the mutation tests you can simply run: 

- ./vendor/bin/infection

If you're not familiar with mutation testing checkout the 
[infection](https://infection.github.io/guide/) page for more informations.
