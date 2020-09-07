BX.ready(function () {
function findVideos() {
        var videos = document.querySelectorAll('.video');

        for (var i = 0; i < videos.length; i++) {
            setupVideo(videos[i]);
        }
    }

    function setupVideo(video) {
        var link = video.querySelector('.video__link');
        if(link !== null){
	        var media = video.querySelector('.video__media');
	        var button = video.querySelector('.video__button');
	        var id = parseMediaURL(media);

	        video.addEventListener('click', function() {
		        var iframe = createIframe(id);

		        link.remove();
		        button.remove();
		        video.appendChild(iframe);
	        });

	        link.removeAttribute('href');
	        video.classList.add('video--enabled');
        }
    }

    function parseMediaURL(media) {

		if(media)
		{
			var regexp = /https:\/\/i\.ytimg\.com\/vi\/([a-zA-Z0-9_-]+)\/maxresdefault\.jpg/i;
			var url = media.src;
			var match = url.match(regexp);
			if(match === null){
				return 0;
			}
			return match[1];
		}
		else{
			return 0;
		}
    }

    function createIframe(id) {
        var iframe = document.createElement('iframe');

        iframe.setAttribute('allowfullscreen', '');
        iframe.setAttribute('allow', 'autoplay');
        iframe.setAttribute('src', generateURL(id));
        iframe.classList.add('video__media');

        return iframe;
    }

    function generateURL(id) {
        var query = '?rel=0&showinfo=0&autoplay=1';

        return 'https://www.youtube.com/embed/' + id + query;
    }

    findVideos();
});