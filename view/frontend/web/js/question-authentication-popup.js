/*
* @Author: Ngo Quang Cuong
* @Date:   2017-09-10 07:49:36
* @Last Modified by:   https://www.facebook.com/giaphugroupcom
* @Last Modified time: 2017-10-20 14:41:46
*/

define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'Magento_Customer/js/customer-data',
    'mage/mage',
    'jquery/ui',
    'jquery/jquery.cookie'
], function ($, modal, customerData) {
    'use strict';

    $.widget('phpcuong.questionAuthenticationPopup', {

        /**
         *
         * @private
         */
        _create: function () {
            var self = this,
                authentication_options = {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    title: this.options.popupTitle,
                    buttons: false,
                    modalClass : 'question_popup_modal'
                };

            modal(authentication_options, this.element);

            $('body').on( 'click', '.question-form-actions button.submit', function() {
                var form = $(self.options.parentFormId);
                form.submit(function (e) {
                    if (form.validation('isValid')) {
                        var customer = customerData.get("customer")(),
                            button = self.element.find('form').find('button.submit span');
                        if (customer.fullname == undefined) {
                            self.element.find('form').find('#detail').remove();
                            self.element.find('form').find('#reply-on-type').remove();
                            self.element.find('form').find('#commentId').remove();
                            self.element.find('form').find('#author_name').val($.cookie('phpcuong.question_author_name'));
                            self.element.find('form').find('#author_email').val($.cookie('phpcuong.question_author_email'));
                            self.element.find('form').find('#commentId').remove();
                            if (form.parents('ul.parent').length > 0) {
                                button.text(self.options.titleButtonAnswer);
                            } else {
                                button.text(self.options.titleButtonQuestion);
                            }
                            form.find('#detail').clone().prependTo(self.element.find('form')).hide();
                            form.find('#reply-on-type').clone().prependTo(self.element.find('form')).hide();
                            form.find('#commentId').clone().prependTo(self.element.find('form')).hide();
                            self.element.modal('openModal');
                            self._setStyleCss();
                        } else {
                            return true;
                        }
                    }
                    return false;
                });
            });

            this.element.find('form').submit(function() {
                if ($(this).validation('isValid')) {
                    self._saveAuthorNameCookie(self.element.find('form').find('#author_name').val());
                    self._saveAuthorEmailCookie(self.element.find('form').find('#author_email').val());
                    return true;
                }
                return false;
            });

            $('body').on('click', '.actions-primary button.cancel', function() {
                $('.question-add').find('form')[0].reset();
                $('.question-add').find('#reply-on-type').val('1');
                $('.question-add').find('#commentId').val('');
                $('.question-add').find('.block-title strong').text(self.options.titleAsking);
                $('.question-add').find('.actions-primary .submit span').text(self.options.titleButtonQuestion);
                $('.question-add').find('button.cancel').hide();
                $('.action-actived').removeClass('action-actived');
                $('.question-add').insertAfter('#product-question-container');
            });

            this._processReplyOn();

            this._resetStyleCss();
        },

        _saveAuthorNameCookie: function(value) {
            $.cookie('phpcuong.question_author_name', value, {expires: this._getCookieExpiryTime()});
        },

        _saveAuthorEmailCookie: function(value) {
            $.cookie('phpcuong.question_author_email', value, {expires: this._getCookieExpiryTime()});
        },

        _getCookieExpiryTime: function() {
            var date = new Date(),
                days = 60;
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            return date;
        },

        _processReplyOn: function() {
            var self = this;
            $('body').on('click', '.reply-on-question, .reply-on-answer', function() {
                var element = null,
                    replyOn = $(this).attr('reply-on');
                if (replyOn == 'question') {
                    element = $(this).parents('ul.reply').next('ul.answers');
                }

                if (replyOn == 'answer') {
                    element = $(this).parents('ul.answers');
                }

                if (!element) {
                    return false;
                }

                $('.question-add').find('.block-title strong').text(self.options.titleAnswering);
                $('.question-add').find('.actions-primary .submit span').text(self.options.titleButtonAnswer);
                var replyFor = '',
                    elementForGetText = $(this).parent().parent().find('> p.table .author-name strong');
                if (elementForGetText.length > 0) {
                    replyFor = '@'+elementForGetText.text()+': ';
                }
                $('.question-add').find('#detail').val(replyFor);
                $('.question-add').find('#reply-on-type').val('2');
                $('.question-add').find('#commentId').val(element.parent().find('ul.question-parent').attr('comment-id'));
                $('.action-actived').removeClass('action-actived');
                $(this).addClass('action-actived');
                element.append($('.question-add'));
                $('.question-add').find('button.cancel').show();
                $('html, body').animate({
                    scrollTop: $('.question-add').offset().top - 50
                }, 300);
            });
        },

        /**
         * Set width of the popup
         * @private
         */
        _setStyleCss: function(width) {

            width = width || 400;

            if (window.innerWidth > 786) {
                this.element.parent().parent('.modal-inner-wrap').css({'max-width': width+'px'});
            }
        },

        /**
         * Reset width of the popup
         * @private
         */
        _resetStyleCss: function() {
            var self = this;
            $( window ).resize(function() {
                if (window.innerWidth <= 786) {
                    self.element.parent().parent('.modal-inner-wrap').css({'max-width': 'initial'});
                } else {
                    self._setStyleCss(self.options.innerWidth);
                }
            });
        }
    });

    return $.phpcuong.questionAuthenticationPopup;
});
