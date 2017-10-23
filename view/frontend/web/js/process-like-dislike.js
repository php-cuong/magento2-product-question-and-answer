/*
* @Author: Ngo Quang Cuong
* @Date:   2017-09-10 07:49:36
* @Last Modified by:   https://www.facebook.com/giaphugroupcom
* @Last Modified time: 2017-10-20 05:58:14
*/

define([
    'jquery',
], function ($) {
    'use strict';

    $.widget('phpcuong.processLikeDislike', {

        /**
         *
         * @private
         */
        _create: function () {
            var self = this;
            $(document).on('click', '.reply .like, .reply .dislike', function() {
                var formData = null;
                if ($(this).attr('is') == 'like') {
                    formData = self._processData($(this), 'like');
                }

                if ($(this).attr('is') == 'dislike') {
                    formData = self._processData($(this), 'dislike');
                }

                if (formData) {
                    self._ajaxSubmit(formData, $(this));
                }

                return false;
            });
        },

        /**
         * Retrieve the data for submitting
         *
         * @private
         */
        _processData: function(element, type) {
            if (element.attr('clicked') == 'false') {
                var formData = new Object();
                formData.type = type;
                switch(type) {
                    case 'like':
                        if (element.attr('like-on') == 'answer') {
                            formData.onType = true;
                            formData.commentID = element.parents('ul.reply').attr('comment-id');
                        }
                        break;
                    case 'dislike':
                        if (element.attr('dislike-on') == 'answer') {
                            formData.onType = true;
                            formData.commentID = element.parents('ul.reply').attr('comment-id');
                        }
                        break;
                    default:
                        break;
                }
                element.attr('clicked', 'true');
                return formData;
            }
            return false;
        },

        /**
         * Submit data by Ajax
         * @private
         */
        _ajaxSubmit: function(formData, element) {
            var self = this;
            $.ajax({
                url: this.options.likeDislikeUrl,
                data: formData,
                showLoader: false,
                type: 'POST',
                dataType: 'json',
                beforeSend: function() {
                    element.find('.number').text('').addClass('loading-number').css(
                        {'background-image': 'url('+self.options.loadingNumberImage+')'}
                    );
                },
                success: function(response) {
                    if (!response.error) {
                        element.find('.number').html(response.total_number).removeClass('loading-number').css(
                            {'background-image': 'none'}
                        ).parents('li').css({'cursor': 'initial', 'color': '#575757'});
                    }
                }
            });
            return false;
        }
    });

    return $.phpcuong.processLikeDislike;
});
