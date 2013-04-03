// game variables
var context;
var map = [];
var currentPosition = {x: 0, y: 0};
var hasUpdate = true;
var canDraw = false;
var moving = false;
var hasMoved = false;
var movePrevPosition = {x: 0, y: 0};
var doSelect = false;
var selectedTile = [];
var drawChunksWidth = 2;
var mouseOnCanvas = false;
var popup = null;
var frameCounter = 0;
var gameStartTime = 0;
var gameTime = 0;

// statistics
var receivedBytes = 0;

// Constants
var chunkSize = 8;
var tileSize = 48;
var tileRenderSize = 64;

// Tile types
var TILE_GRASS = 0;
var TILE_MOUNTAIN = 1;
var TILE_FOREST = 2;

// Tile field indices & field data
var EXTRA_HAS_BUILDING = 1;
var EXTRA_IS_RANDOMIZED = 2;

var EXTRA_DATA_RANDOMIZED = 0;
var EXTRA_DATA_BUILDING = 1;

var controls = {
    fps: 60,
    width: 700,
    height: 500,
    drawing: false,
};

var textureOffsets = [
    // Grass
    [0, 0],
    // Mountain
    [48, 0, 96, 0],
    // Forest
    [144, 0],
];

var textures = {
	// Grass
    grass: {x: 0, y: 0, texture: 'base', type: 'static'},
    // Mountain Variant 1
    mountain_v1: {x: 48, y: 0, texture: 'base', type: 'static'},
    // Mountain Variant 2
    mountain_v2: {x: 96, y: 0, texture: 'base', type: 'static'},
    // Forest
    forrest: {x: 144, y: 0, texture: 'base', type: 'static'},
    // Example for an animated texture:
    animtest: {x: 0, y: 0, texture: 'base', type: 'animated', direction: 'h', length: 4, delay: 50},
    // {x: 0, y: 48, texture: 'name', type: 'animated', direction: 'h', length: 5, delay: 500},
    // would be a horizontal animation in the texture 'name' with 5 images and a delay of 500 ms between them.
    
    // for oversized textures add the width and height attributes. the extended width and height will
    // append to the right and top side of the tile.
};

var tileTypes = {
    // Grass / Animation test
    0: {
    	// layers contains the textures used by this tile.
        layers: ['grass']
    },
    // Mountain
    1: {
        layers: ['grass', 'mountain_v1']
    },
    // Forrest
    2: {
        layers: ['grass', 'forrest']
    },
    // Mountain variant 2
    3: {
    	layers: ['grass', 'mountain_v2']
    },
};

var textureImages = {};

var statImages = {
	total: 0,
	loaded: 0,
};

function initContext(id) {
    if (!ROOT) {
        alert('Blame the php developer! He removed the ROOT variable again.');
    }
    // hide the popup
    $('#game_popup').hide();
    canvas = document.getElementById(id);
    if (canvas && canvas.getContext) {
        // Basic setup
        context = canvas.getContext('2d');
        context.canvas.width = controls.width;
        context.canvas.height = controls.height;
        // initialize event handlers
        $(canvas).mousedown(onMouseDown);
        $(document).mouseup(onMouseUp);
        $(document).mousemove(onMouseMove);
        $(canvas).mouseout(onMouseOut);
        $(canvas).mouseenter(onMouseEnter);
        // Load images
        // base texture (currently testing)
        loadImage('css/texture.png', 'base');
        /*textureImage = new Image();
        textureImage.addEventListener('load', function() {
            canDraw = true;
        }, false);
        textureImage.src = ROOT + '/css/texture.png';*/
       
        // Additional Intialization
        if (initialState) {
            moveToTile(initialState.x, initialState.y);
            if (initialState.controls) {
                controls.fps = initialState.controls.fps ? initalState.controls.fps : controls.fps;
                controls.width = initialState.controls.width ? initalState.controls.width : controls.width;
                controls.height = initialState.controls.height ? initalState.controls.height : controls.height;
            }
        } else {
            console.log('[INFO] No intial state given.');
        }
        console.log('[INFO] Game started.');
        // Start
        update();
        draw();
        // save the game start time
        gameStartTime = new Date().getTime();
    } else {
        console.log('[FATAL] No canvas available.');
        alert('Your browser doesn\'t support drawing with the canvas element!');
    }
}

function onMouseDown(event) {
	if(event.button == 0) {
        moving = true;
        mouseOnCanvas = true;
        hasMoved = false;
        movePrevPosition.x = event.screenX;
        movePrevPosition.y = event.screenY;
        // prevent text selection when dragging
        $('html').addClass('drag');
    }
}

function onMouseUp(event) {
	if(event.button == 0) {
	    if (!hasMoved && mouseOnCanvas) {
	        var offX, offY;
	        // Fix for Firefox not giving offsetX and offsetY
	        if (event.offsetX || event.offsetY) {
	            offX = event.offsetX;
	            offY = event.offsetY;
	        } else {
	            // jquery bugfix lies!
	            offX = event.pageX - $(event.target).position().left;
	            offY = event.pageY - $(event.target).position().top;
	        }
	        var x = offX - context.canvas.width / 2 + currentPosition.x;
	        var y = offY - context.canvas.height / 2 + currentPosition.y;
	        selectedTile = [Math.floor(x / tileSize) + chunkSize / 2, Math.floor(y / tileSize) + chunkSize / 2];
	        tilePos = getTileChunk(selectedTile[0], selectedTile[1]);
	        if (map[tilePos.chunkY] && map[tilePos.chunkY][tilePos.chunkX])
	            onTileSelect(map[tilePos.chunkY][tilePos.chunkX][tilePos.index], tilePos);
	        hasUpdate = true;
	    } else {
	        hasMoved = false;
	    }
	    moving = false;
	}
	// reenable text selection
	$('html').removeClass('drag');
}

function onMouseMove(event) {
	if (moving) {
	    diff = {
	        x: event.screenX - movePrevPosition.x,
	        y: event.screenY - movePrevPosition.y
	    };
	    move(diff.x, diff.y);
	    movePrevPosition.x = event.screenX;
	    movePrevPosition.y = event.screenY;
	    hasMoved = true;
	    //$(popup).hide();
	    //selectedTile = null;
	}
}

function onMouseOut(event) {
	mouseOnCanvas = false;
}

function onMouseEnter(event) {
	mouseOnCanvas = true;
}

/**
 * New tile has been selected. Create the popup for the actions.
 */
function onTileSelect(tile, tilePos) {
    tileType = tile[0];
    var buttons = [];
    if (tileType == TILE_GRASS) {
        buttons.push({text: 'Grass', onClick: function() { console.log('foo'); }});
    }
    if (tileType == TILE_MOUNTAIN) {
        buttons.push({text: 'Mountain'});
    }
    if (tileType == TILE_FOREST) {
    	buttons.push({text: 'Forest'});
    }
    popupSetContent('Tile', buttons, tilePos.tileX, tilePos.tileY);
    $(popup).css(calculatePopupPosition(tilePos.tileX, tilePos.tileY));
}

function popupSetContent(title, buttons) {
    if (!popup)
        popup = $('#game_popup');
    var html = '';
    // html += '<span>' + title + '</span>';
    $.each(buttons, function(index, button) {
        html += '<div class="popup_button">' + button.text + '</div>';
    });
    $(popup).html(html);
    $(popup).show();
}

function update() {
    if (controls.width != canvas.width || controls.height != canvas.height) {
        canvas.width = controls.width;
        canvas.height = controls.height;
    }
    var centerX = getCenterChunkX();
    var centerY = getCenterChunkY();
    retrieveChunks(calculateVisibleChunks());
    var debugText = '';
    debugText += 'Chunk Position: ' + getCenterChunkX() + ' / ' + getCenterChunkY() + '<br>';
    debugText += 'Downloaded map data: ' + receivedBytes + ' bytes' + '<br>';
    debugText += 'Currently loaded images: ' + statImages.loaded + ' of ' + statImages.total + '<br>';
    debugText += 'Current game time: ' + gameTime + '<br>';
    $('#map_display').html(debugText);
    setTimeout('update()', 10);
}
