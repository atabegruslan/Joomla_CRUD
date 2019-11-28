(function($) {

    $(document).ready(function() {

        tripblog.trip = {
            like: function(e, likeValue) {

                var tripblogId = $(e.target).closest('.likes_outer_container').attr('data-tripblog-id');

                var settings = {
                    url: 'index.php?option=com_tripblog&task=trip.ajaxLike',
                    type:'POST',
                    dataType: 'json',
                    data: {
                        likes      : likeValue,
                        tripblogId : tripblogId,
                        task       : 'trip.ajaxLike'
                    },
                    beforeSend: function() {

                    }
                };

                $.ajax(settings)
                    .done(function(data) {
                        $("#ajaxMessages").text(data.message);

                        if (data.hasSucceeded === 1)
                        {
                            $(e.target.closest('td')).find('#avg_like_text').text(data.averageLikes);

                            if ($(".likes_box_outer").length === 1)
                            {
                                $(".likes_box_outer").html(data.likesTable);
                            }
                        }
                    })
                    .fail(function(jqXHR) {
                        console.dir(jqXHR);
                    })
                    .always(function() {
                        //location.reload();
                    });

            },

            setLike: function(target, likeValue) {
                target.closest("div.likes_container").find("span").each(function() {
                    if ($(this).find("input.likes").val() <= likeValue)
                    {
                        $(this).find("i").css('color', 'orange');
                    }
                    else
                    {
                        $(this).find("i").css('color', 'black');
                    }
                });
            },

            initLikes: function() {

                var likes = $('.likes_outer_container');

                likes.each(function() {
                    var tripblogId = $(this).attr('data-tripblog-id');
                    var thisTarget = $(this);

                    var settings = {
                        url: 'index.php?option=com_tripblog&task=trip.ajaxInitLike',
                        type:'POST',
                        dataType: 'json',
                        data: {
                            tripblogId : tripblogId,
                            task       : 'trip.ajaxInitLike'
                        },
                        beforeSend: function() {

                        }
                    };

                    $.ajax(settings)
                        .done(function(data) {
                            //$("#ajaxMessages").text(data.message);

                            if (data.hasSucceeded === 1)
                            {
                                tripblog.trip.setLike($(thisTarget.find(".likes_container").find("span")[0]), data.likes);
                            }
                        })
                        .fail(function(jqXHR) {
                            if (jqXHR.responseJSON.hasSucceeded === 0)
                            {
                                //$("#ajaxMessages").text(jqXHR.responseJSON.message);
                            }
                        })
                        .always(function() {
                            //location.reload();
                        });
                });
            },
        };

        tripblog.trip.initLikes();

        $('input:radio[name="likes"]').change(function(e) {
            var likeValue = $(this).val();

            // then send likeValue to AJAX
            tripblog.trip.like(e, likeValue);

            tripblog.trip.setLike($(this), likeValue);
        });

        $("p.like_button").on('click', function (e) {
            var tripblogId = $(e.target.closest('p.like_button')).data('blog_id');

            var settings = {
                url: 'index.php?option=com_tripblog&task=trip.ajaxGetTally',
                type:'POST',
                dataType: 'json',
                data: {
                    tripblogId : tripblogId,
                },
                beforeSend: function() {

                }
            };

            $.ajax(settings)
                .done(function(data) {
                    $("#like_detail .modal-body").html(data.tally);
                })
                .fail(function(jqXHR) {
                    //console.dir(jqXHR);
                })
                .always(function() {
                    //location.reload();
                });
        });

        $("#like_detail").css("z-index", "-10");

        $("#like_detail").on('shown.bs.modal', function() {
            $("#like_detail").css("z-index", "2000");
        });

        $("#like_detail").on('hidden.bs.modal', function() {
            $("#like_detail").css("z-index", "-10");
        });
    });
})( jQuery );
