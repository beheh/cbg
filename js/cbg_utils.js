/**
 * Utility functions for the map
 */

function randInt(min, max) {
    if (max < min)
        return randInt(max, min);
    else
        return Math.round(Math.random() * (max - min) + min);
}

function move(dX, dY) {
    currentPosition.x -= dX;
    currentPosition.y -= dY;
    // Redraw the image
    hasUpdate = true;
}

function moveTo(x, y) {
    currentPosition.x = x;
    currentPosition.y = y;
    hasUpdate = true;
}

function moveToTile(x, y) {
    moveTo(x * tileSize + tileSize / 2, y * tileSize + tileSize / 2);
}

function moveToChunk(x, y) {
    moveToTile(x * chunkSize, y * chunkSize);
}

function getCenterChunkX() {
    var x = Math.floor((currentPosition.x / tileSize + 4) / chunkSize);
    return x;
}

function getCenterChunkY() {
    var y = Math.floor((currentPosition.y / tileSize + 4) / chunkSize);
    return y;
}

function getTileChunk(tileX, tileY) {
    chunkX = Math.floor(tileX / chunkSize);
    chunkY = Math.floor(tileY / chunkSize);
    x = tileX - chunkX * chunkSize;
    y = tileY - chunkY * chunkSize;
    index = y * chunkSize + x;
    return {tileX: tileX, tileY: tileY, chunkX: chunkX, chunkY: chunkY, x: x, y: y, index: index};
}

function translateX(x) {
    return x - currentPosition.x + context.canvas.width / 2 - chunkSize * 24;
}

function translateY(y) {
    return y - currentPosition.y + context.canvas.height / 2 - chunkSize * 24;
}

function calculateVisibleChunks() {
    var centerX = getCenterChunkX();
    var centerY = getCenterChunkY();
    var chunkWidth = chunkSize * tileSize;
    // be greedy!
    var widthCount = Math.ceil((controls.width * 1.3) / chunkWidth);
    var heightCount = Math.ceil((controls.height * 1.5) / chunkWidth);
    var chunks  = [];
    for (var x = centerX - widthCount / 2; x  <= centerX + widthCount / 2; ++x) {
        for (var y = centerY - heightCount / 2; y <= centerY + heightCount / 2; ++y) {
            chunks.push({x: Math.round(x), y: Math.round(y)});
        }
    }
    return chunks;
}

function calculatePopupPosition(tileX, tileY) {
    var x = translateX(tileX * tileSize);
    var y = translateY(tileY * tileSize) - context.canvas.height - tileSize;
    return {left: x, top: y};
}

function calculateFrame(frames, delay) {
	return Math.round((frames - 1) * ((gameTime % delay) / delay));
}

function isInside (x, y) {
    return (x > currentPosition.x - context.canvas.width / 2 - chunkSize * 24 && x < currentPosition.x + context.canvas.width / 2 + chunkSize * 24) &&
        (y > currentPosition.y - context.canvas.height / 2 - chunkSize * 24 && y < currentPosition.y + context.canvas.height / 2 + chunkSize * 24);
}