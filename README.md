# ReCAPTCHA

This is the **maintained** fork of the [original ReCAPTCHA](https://github.com/google/ReCAPTCHA) repo.

![Demo](http://i.imgur.com/pPNXFgx.gif)

## Description:

The original description:

> ReCAPTCHA is a free CAPTCHA service that protect websites from spam and abuse.
This is Google authored code that provides plugins for third-party integration
with ReCAPTCHA.

Watch the official video for a quick introduction.

[![Video](http://i.imgur.com/tZDwJeB.png)](https://www.youtube.com/watch?v=jwslDn3ImM0)

More info [on the official](http://www.google.com/recaptcha/intro/) Google Page.

## Install

You can just clone it via Git

    git clone git@github.com:wecodemore/ReCAPTCHA.git <your-target-dir>

Or use Composer (recommended).
The Package is [auto-updated from GitHub to Packagist](https://packagist.org/packages/wecodemore/recaptcha)
so every commited/merged change on GitHub is instantly available via Composer.

	php composer.phar install

## Contributing

We accept pull requests. Aligning issues for discussions is welcome.

Please format your commit messages in the following way:

    <topic>(<affected part>) Summary

	One change per line
	Always in present tense

Example

	fix(docs) Link to StackOverflow

	Link to the correct answer instead of the question
	Add two missing commas
	Add a section to explain thing

## API Key

[Create an API key here](https://www.google.com/recaptcha/admin/create).

## Documentation

Link to the [discussion group](http://groups.google.com/group/recaptcha).
Link to the [offical docs page on Google Developers](https://developers.google.com/recaptcha/).

## License:

BSD 3-Clause
