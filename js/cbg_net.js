/**
 * Network code for cbg.
 */

function receiveChunks(result, status, request) {
    receivedBytes += request.responseText.length;
    $.each(result.chunks, function(index, chunk) {
        if (!map[chunk.y])
            map[chunk.y] = [];
        map[chunk.y][chunk.x] = chunk.data;
    });
    hasUpdate = true;
}

var canRequestChunks = true;

function retrieveChunks(chunks) {
    if (!canRequestChunks)
        return;
    requestChunks = [];
    $(chunks).each(function(index, chunk) {
        if (!map[chunk.y]) {
            requestChunks.push(chunk);
        } else {
            if (!map[chunk.y][chunk.x]) {
                requestChunks.push(chunk);
            }
        }
    });
    if(requestChunks.length == 0)
        return;
    canRequestChunks = false;
    $.ajax(ROOT + 'ajax.php', { data: JSON.stringify(requestChunks), type: "POST", success: receiveChunks, complete: function() { canRequestChunks = true; }});
}

/**
 * Forces to reload the chunk
 */
function retrieveChunk(x, y) {
    $.post(ROOT + 'ajax.php', JSON.stringify({x: x, y: y}), receiveChunks);
}

function loadImage(src, name) {
	// we need to load another image
	controls.drawing = false;
	statImages.total += 1;
	// load it
	textureImages[name] = new Image();
	textureImages[name].addEventListener('load', function() {
		statImages.loaded += 1;
		if (statImages.loaded == statImages.total) {
			controls.drawing = true;
		}
	}, false);
	textureImages[name].src = ROOT + src;
	
}
