<h1 align="center">send-email</h1> <br>
<div align="center">
  <strong>Simple PHP HTTP POST email service</strong>
</div>
<div align="center">
  Send simple email with moustache template through POST HTTP requests.
</div>

<div align="center">
  <h3>
    <a href="#">Documentation</a>
    <span> | </span>
    <a href="#contributing">
      Contributing
    </a>
  </h3>
</div>

<div align="center">
  <sub>Built with ❤︎ by
  <a href="https://michael.ravedoni.com/en">Michael Ravedoni</a> and
  <a href="https://github.com/michaelravedoni/send-email/contributors">
    contributors
  </a>
</div>

## Table of Contents

- [Introduction](#introduction)
- [Features](#features)
- [Install](#install)
- [Usage](#usage)
- [Contributing](#contributing)
- [Authors and acknowledgment](#authors-and-acknowledgment)

## Introduction
[![Conventional Commits](https://img.shields.io/badge/Conventional%20Commits-1.0.0-yellow.svg?style=flat-square)](https://conventionalcommits.org)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg?style=flat-square)](https://github.com/michaelravedoni/send-email/blob/master/LICENSE)

Send simple email with Twig template through POST HTTP requests. This is a PHP service that takes the basic elements required to send an email with `mail()` PHP function or SMTP with PHPMailer. The key / values are taken from data parameter and replaced through the template selected by the `template` or `remote_template` variable.

## Features

- Email `.html` templating with `{{...}}` Twig moustaches
- Send email with a POST HTTP request

## Install

Download and unzip the repository content to your host or service. E.g. : `example.com/services/send-email`.

## Usage
HTTP Post parameters :

| Name  | Type | Description | Example |
|---|---|---|---|
| to | string | Email receiver. Required. | `to@example.com` |
| from  | string | Email sender. Required. | `from@example.com` |
| from_name  | string | Email sender name | `John` |
| subject | string | Email subject. Required. | `Example subject` |
| template | string | Template name without html extension and language. You can add your templates in the `/templates` directory.  | `example` |
| lang | string | Template language. Append a `-lang` to the html template.  | `en` |
| remote_template | string | You can fetch a html remote file. | `https://example.com/template.html` |
| message | string | You can write your own message if you don't want to use a template | `Hi, this is my message` |
| data |  Object (JSON) | The key/values are taken from data and replaced through the template. | `{"name": "John Doe", "message": "Un petit message"}`
| transport | string | Transport type. If you have not SMTP server, don't use this parameter. | `smtp` or `mail` |

### As HTTP service

#### Curl
```curl
curl --request POST \
  --url https://example.com/services/send-email/ \
  --header 'content-type: multipart/form-data;' \
  --form 'to=<EMAIL_TO>' \
  --form 'from=<EMAIL_FROM>' \
  --form 'template=<FILE_OR_URL>' \
  --form data=<JSON_DATA> \
  --form subject=<SUBJECT>
```

#### JavaScript (XMLHttpRequest)
```javascript
var data = new FormData();
data.append("to", "<EMAIL_TO>");
data.append("from", "<EMAIL_FROM>");
data.append("template", "<FILE_OR_URL>");
data.append("data", JSON.stringify(<JSON_DATA>));
data.append("subject", "<SUBJECT>");

var xhr = new XMLHttpRequest();
xhr.open("POST", "https://example.com/services/send-email/");
xhr.send(data);
```

#### JavaScript (fetch)
```javascript
var form = new FormData();
form.append("to", "<EMAIL_TO>");
form.append("from", "<EMAIL_FROM>");
form.append("template", "<FILE_OR_URL>");
form.append("data", JSON.stringify(<JSON_DATA>));
form.append("subject", "<SUBJECT>");

fetch("https://example.com/services/send-email/", {
  "method": "POST",
  "headers": {
    "content-type": "multipart/form-data;"
  }
})
.then(response => {
  console.log(response);
})
.catch(err => {
  console.log(err);
});
```

#### Node.js (request)
```javascript
var request = require("request");

var options = {
  method: 'POST',
  url: 'https://example.com/services/send-email/',
  headers: {'content-type': 'multipart/form-data;'},
  formData: {
    to: '<EMAIL_TO>',
    from: '<EMAIL_FROM>',
    template: '<FILE_OR_URL>',
    data: '<JSON_DATA>',
    subject: '<SUBJECT>'
  }
};

request(options, function (error, response, body) {
  if (error) throw new Error(error);
  console.log(body);
});

```

#### HTML form
```html
<form class="" action="https://example.com/services/send-email/" method="post">
  <input type="hidden" name="to" value="<EMAIL_TO>">
  <input type="hidden" name="from" value="<EMAIL_FROM>">
  <input type="hidden" name="subject" value="<SUBJECT>">
  <input type="hidden" name="template" value="<FILE_OR_URL>">
  <!--<input type="hidden" name="message" value="<MESSAGE>">-->
  <input type="hidden" name="data" value=""> <!-- to JSON -->
  <input type="submit" value="Send email">
</form>
```

#### Javascript function
```html
<input type="button" value="Send Email" onclick="sendEmail()">
<script>
function sendEmail() {
	var templateData = {
        data_one: 'One',
        data_two: 'Two',
	};
	var data = new FormData();
	data.append('to', '<TO>');
	data.append('from', '<FROM>');
	data.append('subject', '<SUBJECT>');
	data.append('message', '<MESSAGE>');
	data.append('data', JSON.stringify(templateData));

	var request = new XMLHttpRequest();
	request.open("POST", 'https://example.com/services/send-email/');
	request.send(data);
}
</script>
```

## Contributing

We’re really happy to accept contributions from the community, that’s the main reason why we open-sourced it! There are many ways to contribute, even if you’re not a technical person.

1. Fork it (<https://github.com/lumibib/send-email/fork>)
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request

## Roadmap

- …

## Authors and acknowledgment

* **Michael Ravedoni** - *Initial work* - [michaelravedoni](https://github.com/michaelravedoni)

See also the list of [contributors](https://github.com/lumibib/send-email/contributors) who participated in this project.

## License

[MIT License](https://opensource.org/licenses/MIT)
