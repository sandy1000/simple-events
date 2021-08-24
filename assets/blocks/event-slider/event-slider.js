( function ( blocks, element, data, blockEditor ) {
    var el = element.createElement,
        registerBlockType = blocks.registerBlockType,
        withSelect = data.withSelect,
        useBlockProps = blockEditor.useBlockProps;
 
    registerBlockType( 'pt/simple-event-slider', {
        apiVersion: 2,
        title: 'Event Slider',
        icon: 'megaphone',
        category: 'simple-event-blocks',
        edit: withSelect( function ( select ) {
            return {
                posts: select( 'core' ).getEntityRecords( 'postType', 'simple_event' ),
            };
        } )( function ( props ) {
            var blockProps = useBlockProps();
            var content;
            if ( ! props.posts ) {
                content = 'Loading...';
            } else if ( props.posts.length === 0 ) {
                content = 'No posts';
            } else {
                var post = props.posts[ 0 ];
                content = el( 'a', { href: post.link }, post.title.rendered );
                console.log(post);
            }
 
            return el( 'div', blockProps, content );
        } ),
    } );
} )(
    window.wp.blocks,
    window.wp.element,
    window.wp.data,
    window.wp.blockEditor
);