import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';

registerBlockType('proevent/event-grid', {
    title: 'Event Grid',
    icon: 'calendar',
    category: 'widgets',
    edit() {
        return <div {...useBlockProps()}>📅 Event Grid (Preview)</div>;
    },
    save() {
        return null; // gagamitin yung render_callback sa PHP
    },
});
