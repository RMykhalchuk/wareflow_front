$(document).ready(function () {
    var avatar = document.getElementById('avatar');
    var image = document.getElementById('image');
    var input = document.getElementById('company-upload1-cropper');

    var $modal = $('#modal');
    var cropper;

    input.addEventListener('change', function (e) {
        const size = (this.files[0].size / 1024 / 1024).toFixed(2);

        if (size > 0.8) {
            input.val('');
            alert('Файл повинен мати розмір не більше 800kB');
        }
        var files = e.target.files;
        var done = function (url) {
            input.value = '';
            image.src = url;
            //$alert.hide();
            $modal.modal('show');
        };
        var reader;
        var file;

        if (files && files.length > 0) {
            file = files[0];
            company_img = $('#company-upload1-cropper')[0].files[0];
            console.log(company_img);
            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    $modal
        .on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 2,
            });
        })
        .on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });

    document.getElementById('crop').addEventListener('click', function () {
        var initialAvatarURL;
        var canvas;

        $modal.modal('hide');

        if (cropper) {
            canvas = cropper.getCroppedCanvas({
                width: 100,
                height: 100,
            });

            avatar.src = canvas.toDataURL();

            // canvas.toBlob(function (blob) {
            //     var formData = new FormData();
            //
            //     formData.append('avatar', blob, 'avatar.jpg');
            //     formData.append('iamge', blob, 'avatar.jpg');
            //
            // });
        }
    });
});
