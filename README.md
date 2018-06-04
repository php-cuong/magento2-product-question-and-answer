# Magento 2 Product Questions Free
This is an awesome module, It allows Customer or Guest to submit questions and answers for the product on the product detail page.

## Features for this extension:
### This is a version non fully of the Product Question And Answer Pro extension for Magento 2
- You can get the full version of this extension in the link **https://github.com/php-cuong/magento2-product-question-and-answer-pro**

### Frontend:
- Display the form allow to submit a question and an answer on the product detail page.
- Display the list of questions and answers for the product on the product detail page
- Display the list of questions and answer on my product questions page.

### Backend:
- Allow setting Guests and Customers to Write Question or Answer.
- Allow setting Questions per Page on List Default Value
- Allow setting Auto approval new question and answer.
- Allow setting the Question Rules Page.

## Introduction installation:

### 1 - Installation Magento 2 Product Questions extension
#### Manual Installation
Install Product Questions extension for Magento2
 * Download the extension
 * Unzip the file
 * Create a folder {Magento root}/app/code/PHPCuong/ProductQuestionAndAnswer
 * Copy the content from the unzip folder


##### Using Composer

```
composer require php-cuong/magento2-product-question-and-answer

```

### 2 - Enable FAQ extension
 * php bin/magento module:enable PHPCuong_ProductQuestionAndAnswer
 * php bin/magento setup:upgrade
 * php bin/magento cache:clean
 * php bin/magento setup:static-content:deploy

### 3 - See results
Goto the product detail page.
Administrator goto STORES -> Configuration -> Catalog -> Catalog -> Product Questions

## ScreenShot
![ScreenShot](https://github.com/php-cuong/magento2-product-question-and-answer/blob/master/Screenshot/configuration.png)
![ScreenShot](https://github.com/php-cuong/magento2-product-question-and-answer/blob/master/Screenshot/question-list.png)
![ScreenShot](https://github.com/php-cuong/magento2-product-question-and-answer/blob/master/Screenshot/sending-information.png)
![ScreenShot](https://github.com/php-cuong/magento2-product-question-and-answer/blob/master/Screenshot/recent-questions.png)
![ScreenShot](https://github.com/php-cuong/magento2-product-question-and-answer/blob/master/Screenshot/my-product-questions.png)
