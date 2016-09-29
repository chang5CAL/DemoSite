var DocumentLibrary = function(userOptions){
    var id, options, defaultOptions, invisibleDom, self = this, isLoaded = false,
        listAndOpenInSameAnchor, anchorSizes, $listAnchor, $messageWrapper, $filterTypes,
        $messageSpace, $search, $clearSearch, $sortTypes, $prevList = null,
        hasNoResults = false, $viewTypes = "";

    id = uniqueId();

    anchorSizes = {
        150:'tiny',
        250:'x-small',
        400:'small',
        600:'medium',
        800:'large',
        1200:'x-large',
        1600:'2x-large',
        2000:'3x-large'
    };



    defaultOptions = {
        debug:false,
        $anchor:null,
        previewTilesNumColumns:4,
        listType:'list',
        $openItemAnchor:userOptions.$anchor,
        openInModal:false,
        path: 'document-library/',
        openItemWidth:function(doc){
            return doc.$anchor.width() * .8
        },
        documentViewerOptions:{}
    };

    options = $.extend(true, {}, defaultOptions, userOptions);

    //TODO: These options need to be set externally
    var documentViewerOptions = $.extend({}, {path:options.path, debug:options.debug}, options.documentViewerOptions);

    var viewer = new DocumentViewer(documentViewerOptions);

    //we need to know if we'll be opening items in the same container the list is shown in
    listAndOpenInSameAnchor = options.$anchor[0] == options.$openItemAnchor[0];



    function debugMessage(msg) {
        if (options.debug && window.console) {
            console.log('DOCUMENT LIBRARY: ' + msg);
        }
    }

    function uniqueId() {
        return new Date().getTime() + '-' + Math.floor(Math.random() * (100000 - 1 + 1)) + 1;
    }

    function renderList(items){
        var isSearchRender = items ? true : false,
            listType = options.listType;

        console.log("RenderList items:");
        console.log(items);

        $listAnchor.empty();

        if(!isSearchRender || !$.isEmptyObject(items)){
            hasResults();

            if(listType == 'previews') {
                renderPreviewTiles(items);
            } else if (listType == 'tiles') {

                // setup the options for the view types

                // seperate the views...
                if ($viewTypes == "detail") {
                    console.log("detail");
                    // TODO create click container over icon and filename
                    renderDetailTiles(items);
                } else if ($viewTypes == "double") {
                    console.log("2 column");
                    renderTiles(items, 2);
                } else if ($viewTypes == "triple") {

                    console.log("3 column");
                    renderTiles(items, 3);
                } else {
                    console.log("1 column");
                    renderTiles(items, 1);                    
                }
            } else {
                renderLineItems(items);
            } 
        }
        else noResults();

    }

    function noResults(){
        hasNoResults = true;
        $listAnchor.addClass('empty');
    }

    function hasResults(){
        if(hasNoResults === true){
            hasNoResults = false;
            $listAnchor.removeClass('empty');
        }

    }

    function setSizeClass() {
        var width = options.$anchor.width(),
            fontSizeClass = anchorSizes['2000'];

        $.each(anchorSizes, function (classWidth, sizeClass) {
            if (width <= parseInt(classWidth, 10)) {
                fontSizeClass = sizeClass;
                return false;
            }
        });

        options.$anchor.attr('data-size', fontSizeClass);
        $listAnchor.attr('data-size', fontSizeClass);

    }

    function renderTiles(items, columns) {
        var library = items ? items : self.library,
            $anchor = $listAnchor;


        //$anchor.append("<table>");
        $anchor.addClass('document-library-grid-list');
        var content = "<table>";
        //console.log($anchor);
        var colIndex = 0;
        console.log("start");
        $.each(library, function(i, item) {
            var details, markup, item, index, col;
            var newRowOpen = ""
            var newRowEnd = "";
            console.log(i);
            item = library[i];

            if (typeof item.key === 'undefined') {
                index = i;
            } else {
                index = item.key;
            }

            if (colIndex % columns == 0) {
                newRowOpen = "<tr>";
            } 

            // for the event of single
            if (colIndex % columns == columns - 1 && colIndex == (library.length - 1)) {
                // at the end of the column and the list
                newRowEnd = "</tr></table>";
            } else if (colIndex % columns == columns - 1) {
                // at the end of the column, but not the list
                newRowEnd = "</tr>";
            } else if (colIndex == (library.length - 1)) {
                // at the end of the list
                newRowEnd = "</tr></table>";
            }

            col = (colIndex % columns) + 1;

            // use col to create the size of the row
            markup = '<td><div class="document-library-item ' + item.details.type + ' document-library-item-column-' + columns + ' ' + item.details.extension + '" data-index="'+ index +'">' +
                '<div class="document-library-item-inner ' + 'document-library-item-inner-column-' + columns + '">' +
                '<div class="document-icon-extension ' + 'document-icon-extension-column-' + columns + '">' + item.details.extension + '</div>' +
                '</div>' +
                '<div class="document-library-filename">' + item.details.name + '</div>' +
                '</div></td>';

            content += newRowOpen + markup + newRowEnd;
            colIndex++;
        });
        $anchor.append(content);
        console.log("end");
    }

    function renderDetailTiles(items) {
        var library = items ? items : self.library,
            $anchor = $listAnchor;


        //$anchor.append("<table>");
        $anchor.addClass('document-library-grid-list');
        $.each(library, function(i, item) {
            var details, markup, item, index;
            console.log(i);
            item = library[i];

            if (typeof item.key === 'undefined') {
                index = i;
            } else {
                index = item.key;
            }

            // use col to create the size of the row
            markup = '<div class="document-library-item document-library-item-detail ' + item.details.type + ' ' + item.details.extension + '" data-index="'+ index +'">' +
                '<div class="document-icon-extension document-icon-extension-detail' + '">' + item.details.extension + '</div>' +
                '<div class="document-library-filename">' + item.details.name + '</div>' +
                '</div>';

            $anchor.append(markup);
        });
    }

    function renderLineItems(items){
        var library = items ? items : self.library,
            $anchor = $listAnchor;

        $anchor.addClass('document-library-line-item-list');

        $.each(library, function(i, item){
            var details, markup;

            details = viewer.getDetails(library[i]);
            markup = '<div class="document-library-item ' + details.type + ' ' + details.extension + '" data-index="'+ i +'">' +
                '<div class="document-icon-extension">' + details.extension + '</div>' +
                '<div class="document-library-filename">' + details.name + '</div>' +
                '</div>';

            $listAnchor.append(markup);
        });

    }

    function renderPreviewTiles(items){
        var $anchor, numColumns, anchorWidth, columnWidth, columnMarkup, columnPadding, columnHeights,
            library = items ? items : self.library, scrollbarWidth;

        $anchor = $listAnchor;

        invisibleDom = new InvisibleDom(true);

        numColumns = options.previewTilesNumColumns;

        //we need to subtract the scrollbar width just in case it's showing, otherwise the columns will be too big.
        //it seems like this could be avoided with some css magic, but the solution is unclear
        scrollbarWidth = invisibleDom.scrollbarWidth();
        anchorWidth = $anchor.width() - scrollbarWidth;

        columnWidth = Math.floor(anchorWidth/numColumns);
        columnMarkup = '<div class="document-library-column" style="width:' + columnWidth + 'px;"></div>';
        columnHeights = [];


        var column = invisibleDom.element($(columnMarkup));
        columnPadding = column.getHorizontalPadding();

        invisibleDom.destroy();

        if(anchorWidth == 0){
            debugMessage('Anchor width is 0. Please update your css to give your anchor a width');
            return;
        }


        //manually creating a masonry layout using columns. The algorithm is simple. The space is separated into columns
        //each time an item is added, we add it to the shortest column.
        $anchor.addClass('document-library');
        $anchor.addClass('document-library-preview-list');


        function createColumns(){
            for(var i = 0; i < numColumns; i++){
                $anchor.append(columnMarkup);
                columnHeights.push(0);
            }
        }


        function getNextColumn(){
            var minIndex = 0;

            for(var i = 0; i < columnHeights.length; i++){
                var height = columnHeights[i];

                if(height < columnHeights[minIndex])
                    minIndex = i;
            }

            return minIndex;
        }

        function calculateColumnHeight($column, columnIndex){
            columnHeights[columnIndex] = $column.height();
        }

        var currentlyLoading = 0;

        var docs = [];

        function initLoading(){
            var $container, $column;

            $.each(library, function(i, item){
                $container = $('<div class="document-library-item" data-index="' + i + '"></div>');

                var nextColumnIndex = getNextColumn();

                $column = $anchor.children().eq(nextColumnIndex);

                $column.append($container);

                var doc = viewer.initRender(library[i].path, {
                    $anchor:$container,
                    autoPlay:false,
                    width:columnWidth - columnPadding.total
                });

                docs.push(doc);

                calculateColumnHeight($column, nextColumnIndex);
            });
        }
        //;kjfi 099;

        function startLoadingNext(){

        }
        function loadNext(){
            if(currentlyLoading >= docs.length)
                return;

            docs[currentlyLoading].load();

            $.when(docs[currentlyLoading].isLoaded).always(function(){
                currentlyLoading++;

                loadNext();
            });
        }

        function reorganize(){
            //redo columns when an image loads?
        }


        createColumns();
        initLoading();
        loadNext();
    }

    function buildBaseLayout(){
        var $markup, markup;
        markup = '<div class="document-library">' +
            '<div class="document-library-filter">' +
            '<div class="document-library-search"><input class="search-field" type="text" placeholder="Search"></div>' +
            '<div class="filter-types">' +
                '<h4>Filters:</h4>' +
                '<span class="filter-documents" data-type="document"></span>' +
                '<span class="filter-images" data-type="image"></span>' +
                '<span class="filter-audio" data-type="audio"></span>' +
                '<span class="filter-video" data-type="video"></span>' +
                '<span class="filter-stl" data-type="stl"></span>' +
            '</div>' +
            '<div class="sort-types">' +
                '<h4>Sort By:</h4>' +
                '<span class="sort-date" data-type="date"></span>' +
                '<span class="sort-name" data-type="name"></span>' +
                '<span class="sort-type" data-type="type"></span>' +
                '<span class="sort-id" data-type="id"></span>' +
            '</div>' +
            '<div class="view-types">' +
                '<h4>View By:</h4>' +
                '<span class="view-single" data-type="single"></span>' +
                '<span class="view-double" data-type="double"></span>' +
                '<span class="view-triple" data-type="triple"></span>' +
                '<span class="view-detail" data-type="detail"></span>' +
            '</div>' +
            '<div class="filter-message"><div class="filter-message-text"></div><span class="clear-message" title="Clear search results"></span></div>' +
            '</div>' +
            '<div class="document-library-list"></div>' +
            '<div class="document-library-no-results">No Results</div>' +
            '</div>';

        $markup = $(markup);

        $listAnchor = $markup.find('.document-library-list');
        $messageWrapper = $markup.find('.filter-message');
        $messageSpace = $markup.find('.filter-message-text');
        // TODO look for where we go to filter our list
        $search = $markup.find('input');
        $clearSearch = $markup.find('.clear-message');
        $filterTypes = $markup.find('.filter-types');
        $sortTypes = $markup.find('.sort-types');

        options.$anchor.append($markup);
    }

    function showMessage(message){
        $messageSpace.text(message);
        $messageWrapper.addClass('showing');
    }

    function hideMessage(){
        $messageWrapper.removeClass('showing');
    }

    function filter(term){
        if(term && term.length){
            showMessage("Search for '" + term + "'");
            var matches = {};
            $.each(self.library, function(i, item){
                if(item.details.name.toLowerCase().indexOf(term.toLowerCase()) != -1){
                    matches[i] = item;
                }
            });

            renderList(matches);
        }
        else {
            $search.val('');
            hideMessage();
            renderList();
        }
    }

    function filterType(type){
        var matches = {}, typeTextPlural, documentSearchTypes;

        typeTextPlural = type == 'document' || type == 'image' ? type + 's' : type;

        documentSearchTypes = ['pdf', 'txt', 'docx', 'doc','stl'];

        showMessage("Showing " + typeTextPlural);

        $.each(self.library, function(i, item){

            if(type == 'document'){
                if($.inArray(item.details.extension, documentSearchTypes) !== -1){
                    matches[i] = item; //push(item);
                }
            }
            else{
                if(item.details.type == type){
                    matches[i] = item; //push(item);
                }
            }
        });
        $prevList = matches;

        renderList(matches);
    }

    // sorts the currently used library by a specific value
    // returns a list that is sorted by the type
    function sortLibrary(library, type) {
        //TODO add sorting options
        //Types of sorting: Date,Name,Type,Id
        sortedLibrary = [];
        var i = 0;
        var currentLeast; //Currently sorting from least to most
        var currentPos;
        var compStr;
        var used = {};

        console.log('library: ');
        console.log(library);
        

        for (keys in library) {
            sortedLibrary.push(library[keys]);
            sortedLibrary[i].key = parseInt(keys);

            console.log(sortedLibrary);
            console.log(keys);
            i++;
        }

        if (type == "type") {
            // sort by type
            sortedLibrary.sort(function(first, second){
                return first.details.extension.localeCompare(second.details.extension);
            });
        } else if (type == "name") {
            // sort by name
            sortedLibrary.sort(function(first, second){
                return first.details.name.localeCompare(second.details.name);
            });
        } else if (type == "date") { 
            //Sorting newest to oldest
            sortedLibrary.sort(function(first, second){
                return second.date - first.date;
            });
        } else if (type == "id") { 
            //Sorting smallest id to largest
            console.log(sortedLibrary);
            sortedLibrary.sort(function(first, second){
                return first.id - second.id;
            });
        }

        for (i = 0; i < sortedLibrary.length; i++) {
            used[sortedLibrary[i].key] = sortedLibrary[i];
        }
        return sortedLibrary;
    }

    // Change self.library to the format of a filtered list so that
    // we can sort it and then pass it back into renderlist
    function makeLibraryFormat() {
        // TODO format self.library so that it appears like this:
        // Object {0: Object, 1: Object, 5: Object}
        return self.library
    }

    function initLibrary(){
        for(var i = 0; i < self.library.length; i++){
            var details, markup;

            details = viewer.getDetails(self.library[i]);

            //store the details on the library item
            self.library[i].details = details;
        }
    }

    function bindEvents(){
        options.$anchor.on('click', '.document-library-item', function(e){
            var $this = $(this),
                index;

            //don't do anything if we click the menu or controls on audio/video
            if($(e.target).is('.document-viewer-menu, .jp-play, .jp-pause, .progress-bg, .progress'))
                return;

            index = $this.data('index');

            openDocument(index);
        });

        options.$anchor.on('click', '.open-item-back', function(){

            clearCanvas();
            buildBaseLayout();
            renderList();
        });

        options.$anchor.on('keyup', '.search-field', function(){
            filter($search.val());
        });

        options.$anchor.on('click', '.clear-message', function(){
            filter();
        });

        options.$anchor.on('click', '.filter-types span', function(){
            var type = $(this).data('type');
            filterType(type);
        });

        // creates event listener to sort the list we're viewing
        options.$anchor.on('click', '.sort-types span', function() {
            var type = $(this).data('type');
            // relies on matches to be global to be able to
            // sort the data and then recall the render
            var library = $prevList ? $prevList : makeLibraryFormat();
            // recall the render with our new sorted list
            console.log(library);
            // TODO uncomment
            renderList(sortLibrary(library, type));
        });

        // creates event listener to set the view options
        options.$anchor.on('click', '.view-types span', function() {
            var viewType = $(this).data('type');
            // relies on matches to be global to be able to
            // sort the data and then recall the render
            // TODO should probably save the new sorted list to prevList
            var library = $prevList ? $prevList : makeLibraryFormat();
            $viewTypes = viewType;
            //, TODO deal with what happens if the user is already sorted as we haven't
            // saved the sorted instance
            renderList(library);
        });

        $(window).on('resize.' + id, function(){
            setSizeClass();
            renderList();
        });


    }

    function clearCanvas(){
        options.$openItemAnchor.empty();
    }

    function openDocument(index){
        var $openItem, $name, $openItemAnchor,
            extraClasses = listAndOpenInSameAnchor === false ? 'list-and-open-in-separate-containers' : '',
            openItemMarkup = '<div class="document-library-open-item ' + extraClasses + '">' +
                '<div class="open-item-menu">' +
                '<span class="open-item-back">< Back</span>' +
                '<span class="open-item-name"></span>' +
                '</div>' +
                '<div class="open-item"></div>' +
                '</div>';

        $openItem = $(openItemMarkup);
        $name = $openItem.find('.open-item-name');
        $openItemAnchor = $openItem.find('.open-item');


        if(!jQuery.contains(document,  options.$openItemAnchor[0])){
            debugMessage('Open Item Anchor does not exist in the document. Please check your value for the \'$openItemAnchor\' parameter and make sure it is a node that exists in the page');
            return false;//is not in the document')
        }


        if(options.openInModal !== true){
            clearCanvas();
            options.$openItemAnchor.html($openItem);
        }

        console.log("Index: ");
        console.log(index);
        var doc = viewer.load(self.library[index].path, {
            isModal:options.openInModal,
            $anchor:$openItemAnchor,
            width:options.openItemWidth,
            setUnsupportedSizeAsSquare:false,
            show: self.library[index].show
        });

        $name.text(doc.name);
    }

    function load(library){
        if(isLoaded)
            return;

        self.library = library;
        initLibrary();

        buildBaseLayout();
        setSizeClass();
        renderList();
        bindEvents();

        isLoaded = true;
    }

    function destroy(){
        $(window).off('resize.' + id);
        options.$anchor.empty();

        if(!listAndOpenInSameAnchor)
            options.$openItemAnchor.empty();
    }
    return{
        load:load,
        destroy:destroy
    }
};