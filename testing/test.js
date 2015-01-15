            var $elements = [$('.Task'), $('.Event'), $('.Resource')];
            
            $(".Task, .Event, .Resource").draggable({
                start: function (ev, ui) {
                    var $elem
                    for (var i in $elements) {
                        $elem = $elements[i];
                        if ($elem[0] != this) {
                            $elem.data('dragStart', $elem.offset());
                        }
                    }
                },
                drag: function (ev, ui) {
                    var xPos, $elem,
                    deltaX = ui.position.left - ui.originalPosition.left;
                    deltaY = ui.position.top - ui.originalPosition.top;
                    for (var i in $elements) {
                        $elem = $elements[i];
                        if ($elem[0] != this) {
                            $elem.offset({
//                                top: $elem.data('dragStart').top,
                                top: Math.max($elem.data('dragStart').top + deltaY, 8),
                                left: Math.max($elem.data('dragStart').left + deltaX, 8)
                            });
                        }
                    }
                }
            });
