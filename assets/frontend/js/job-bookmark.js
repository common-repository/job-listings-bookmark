jQuery(document).ready(function ($) {
    'use strict';
    $('body').on('click', '.jlt-btn-bookmark', function () {
        var $this = $(this);
        $this.find('.jlt-icon').addClass('jltfa-spinner jltfa-spin');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: jltMemberL10n.ajax_url,
            data: {
                action: 'jlt_bookmark_job',
                security: $this.attr('data-security'),
                job_id: $this.attr('data-job-id')
            },
            success: function (data) {
                $this.find('.jlt-icon').removeClass('jltfa-spinner jltfa-spin');
                if (data.success == true) {
                    if ('bookmarked' == data.status) {
                        $this.removeClass('jlt-bookmarked');
                        $this.find('.jlt-icon').removeClass('jltfa-plus-circle');
                        $this.find('.jlt-icon').addClass('jltfa-minus-circle');
                    } else {
                        $this.addClass('jlt-bookmarked');
                        $this.find('.jlt-icon').addClass('jltfa-plus-circle');
                        $this.find('.jlt-icon').removeClass('jltfa-minus-circle');
                    }
                }
                $this.closest('.jlt-bookmark').find('.jlt-bookmark-result').show().html(data.message);
                $this.find('.jlt-bookmark-label').html(data.message_text);
            },
            complete: function () {

            },
            error: function () {
            }
        });

        return false;
    });
});