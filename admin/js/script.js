$(document).ready(function () {

    setTimeout(function () {
        $('#message').remove('.show');
        $('#message').add('.hide');
    }, 4000);

    $('#allOrganizations').DataTable({
        ajax: 'ajax/getOrganizations.php'
    });

    $('#allCities').DataTable({
        ajax: 'ajax/getCities.php'
    });

    $('#allUsers').DataTable({
        ajax: 'ajax/getUsers.php'
    });

    $('#allAttractions').DataTable({
        ajax: 'ajax/getAttractions.php'
    });

    $('#allInfos').DataTable({
        ajax: 'ajax/getInfos.php'
    });

    $('body').on('click', '.deleteAttraction', function () {
        var id = $(this).data('id');
        let name = $(this).data('name');
        let answer = confirm('Do you want to delete attraction with name ' + name + ' ?');
        if (answer) {
            $.post('ajax/deleteData.php', {id_attraction: id});
            $('#allOrganizations').DataTable().ajax.reload();
        }
    });

    $('body').on('click', '.banUser', function () {
        var id = $(this).data('id');
        let name = $(this).data('name');
        let answer = confirm('Do you really want to ban ' + name + '  ?');
        if (answer) {
            $.post('ajax/deleteData.php', {id_user_ban: id});
            $('#allUsers').DataTable().ajax.reload();
        }
    });
    $('body').on('click', '.unbanUser', function () {
        var id = $(this).data('id');
        let name = $(this).data('name');
        let answer = confirm('Do you really want to unban ' + name + '  ?');
        if (answer) {
            $.post('ajax/deleteData.php', {id_user_unban: id});
            $('#allUsers').DataTable().ajax.reload();
        }
    });

    $('body').on('click', '.deleteCity', function () {
        var id = $(this).data('id');
        let name = $(this).data('name');
        let answer = confirm('Do you really want to delete ' + name + ' city ?');
        if (answer) {
            $.post('ajax/deleteData.php', {id_city: id});
            $('#allCities').DataTable().ajax.reload();
        }
    });

    $('body').on('click', '.deleteOrganization', function () {
        var id = $(this).data('id');
        let name = $(this).data('name');
        let answer = confirm('Do you want to delete organization with name ' + name + ' ?');
        if (answer) {
            $.post('ajax/deleteData.php', {id_organization: id});
            $('#allOrganizations').DataTable().ajax.reload();
        }
    });

    $('body').on('click', '.editCity', function () {
        var id = $(this).data('id');
        let options = {
            backdrop: 'static',
            keyboard: true,
            show: false
        };

        $.get('ajax/editCityModal.php', {id: id}, function (html) {
            $('#cityModal').html(html);
            $('#cityModal').modal('show', options);
            $('#cityModal').on('shown.bs.modal', function () {
                $('#name').focus();
            })
        });
    });

    $('body').on('click', '.editAttraction', function () {
        var id = $(this).data('id');
        let options = {
            backdrop: 'static',
            keyboard: true,
            show: false
        };

        $.get('ajax/editAttractionsModal.php', {id: id}, function (html) {
            $('#attractionModal').html(html);
            $('#attractionModal').modal('show', options);
            $('#attractionModal').on('shown.bs.modal', function () {
                $('#name').focus();
            })
        });
    });
    $('body').on('click', '.editOrganization', function () {
        var id = $(this).data('id');
        let options = {
            backdrop: 'static',
            keyboard: true,
            show: false
        };

        $.get('ajax/editOrganizationsModal.php', {id: id}, function (html) {
            $('#organizationsModal').html(html);
            $('#organizationsModal').modal('show', options);
            $('#organizationsModal').on('shown.bs.modal', function () {
                $('#name').focus();
            })
        });
    });

    $('body').on('submit', '#attraction', function (event) {
            if ($('#name').val().trim().length === 0) {
                $('#name').val('');
                $('#name').focus();
                errorMessage('<strong>Error!</strong> Insert name for attraction!');
                return false;
            }
            if ($('#address').val().trim().length === 0) {
                $('#address').val('');
                $('#address').focus();
                errorMessage('<strong>Error!</strong> Insert address for attraction!');
                return false;
            }
            if ($('#description').val().trim().length === 0) {
                $('#description').val('');
                $('#description').focus();
                errorMessage('<strong>Error!</strong> Insert description for attraction!');
                return false;
            }

            $.post("ajax/updateAttraction.php", $(this).serialize(), function (data) {
                $("#message").html(data.message);
                $('#message').removeClass();
                $("#message").addClass(data.aclass);
                $('#message').fadeIn(300);
                $("#message").delay(1000).fadeOut(300);
                $('#allAttractions').DataTable().ajax.reload();
            }, "json");
            event.preventDefault();
        }
    )
    ;
    $('body').on('submit', '#city', function (event) {
            event.preventDefault();
            if ($('#name').val().trim().length === 0) {
                $('#name').val('');
                $('#name').focus();
                errorMessage('<strong>Error!</strong> Insert name for city');
                return false;
            }
            var formImage = new FormData(this);

            formImage.append('file', $('#file')[0].files[0]);

            $.ajax({
                type: 'POST',
                url: 'ajax/updateCity.php',
                data: formImage,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (data) {
                    $("#message").html(data.message);
                    $('#message').removeClass();
                    $("#message").addClass(data.aclass);
                    $('#message').fadeIn(300);
                    $("#message").delay(1000).fadeOut(300);
                    $('#allCities').DataTable().ajax.reload();
                }, error: function (xhr, status, error) {
                    console.error('AJAX Request Error:', status, error);

                    // Display a user-friendly error message
                    $("#message").html('<strong>Error!</strong> An error occurred during the request.');
                    $('#message').removeClass().addClass('alert alert-danger');
                    $('#message').fadeIn(300).delay(2000).fadeOut(300);
                }

            });
        }
    )
    ;
    $('body').on('submit', '#organization', function (event) {
            if ($('#name').val().trim().length === 0) {
                $('#name').val('');
                $('#name').focus();
                errorMessage('<strong>Error!</strong> Insert name for organization');
                return false;
            }
            if ($('#email').val().trim().length === 0) {
                $('#email').val('');
                $('#email').focus();
                errorMessage('<strong>Error!</strong> Insert email for organization');
                return false;
            } else if (!isValidEmail($('#email').val().trim())) {
                errorMessage('<strong>Error!</strong> Email is in bad format');
                return false;
            } else if (!($('#email').val().trim().includes("@org.com"))) {
                errorMessage('<strong>Error!</strong> Change domain to @org.com');
                return false;
            }

            let password = $('#password').val().trim();

            if (password.length > 0) {
                if (password.length < 8) {
                    $('#password').val('');
                    $('#password').focus();
                    errorMessage('<strong>Error!</strong> Minimum length for password is 8 characters! ');
                    return false;
                }
            }

            $.post("ajax/updateOrganization.php", $(this).serialize(), function (data) {
                $("#message").html(data.message);
                $('#message').removeClass();
                $("#message").addClass(data.aclass);
                $('#message').fadeIn(300);
                $("#message").delay(1000).fadeOut(300);
                $('#password').val('');
                $('#allOrganizations').DataTable().ajax.reload();
            }, "json");
            event.preventDefault();
        }
    )
    ;

    function errorMessage(message) {
        $("#message").html(message);
        $('#message').removeClass();
        $("#message").addClass("alert alert-danger");
        $('#message').fadeIn(300);
        $("#message").delay(1000).fadeOut(300);
    }
});
const isValidEmail = (email) => {
    let rex = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/;
    return rex.test(email);
}
