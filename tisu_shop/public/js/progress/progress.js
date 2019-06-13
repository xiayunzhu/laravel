(function($) {
    $.fn.extend({
        loadStep: function(params) {
            var stepArr = params.steps;
            var $this = $(this);
            var createStep = function(len) {
                var step = '<ul class="eStep"></ul>';
                var $step = $(step);

                for (var i = 0; i < len; i++) {
                    var stepItem = '<li class="eStep-item"></li>';
                    $step.append(stepItem);
                }
                $this.append($step);
            }
            var createPoint = function(stepArr) {
                var pointWarp = '<div class="eStep--point-warp"></div>';
                $this.append(pointWarp);
                var length = stepArr.length;
                var everyStepLength = $this.width() / (length - 1);
                var everyWidth = $this.width() / length;
                $(stepArr).each(function(index, item) {
                    var itemHtml = ' <span class="eStep--point-item">' +
                        // '<i class="eStep--point">' + (index + 1) + '</i>' +
                        '<i class="eStep--point"></i>' +
                        '<i class="eStep--text">' + item.text + '</i>' +
                        '</span>';
                    var $itemHtml = $(itemHtml);
                    $itemHtml.css({
                        'left': index * everyStepLength + 'px',
                        width: everyWidth + 'px'
                    });
                    $('.eStep--point-warp').append($itemHtml);
                })
            }
            var createProgress = function() {
                var sProgress = '<div class="eStep-progress"></div>';
                $this.append(sProgress);
            }
            createStep(stepArr.length - 1);
            createProgress();
            createPoint(stepArr);
        },
        setStep: function(step) {
            var setPoint = function() {
                var $stepPointItem = $('.eStep--point-item');
                for (var j = 0; j < $stepPointItem.length; j++) {
                    var $point = $stepPointItem.eq(j).find('.eStep--point');
                    if (j <= step) {
                        $point.addClass('done');
                    }
                }
            }
            setPoint();
            var $this = $(this);
            var setProgress = function() {
                var $progress = $this.find('.eStep-progress');
                var totalWidth = $this.width();
                var allLength = $this.find('.eStep-item').length;
                var everyProgressLength = totalWidth / allLength;
                if (step > allLength) {
                    step = allLength;
                }
                $progress.animate({
                    width: everyProgressLength * step
                });
                //$progress.width(everyProgressLength*step);
            }
            setProgress();

        }
    })
})(jQuery)