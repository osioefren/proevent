import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, TextControl, SelectControl } from '@wordpress/components';

registerBlockType('proevent/event-grid', {
    title: 'Event Grid',
    icon: 'calendar',
    category: 'widgets',
    attributes: {
        limit: { type: 'number', default: 6 },
        category: { type: 'string', default: '' },
        order: { type: 'string', default: 'ASC' },
    },
    edit: ({ attributes, setAttributes }) => {
        return (
            <>
                <InspectorControls>
                    <PanelBody title="Event Grid Settings">
                        <RangeControl
                            label="Number of Events"
                            value={attributes.limit}
                            onChange={(value) => setAttributes({ limit: value })}
                            min={1}
                            max={20}
                        />
                        <TextControl
                            label="Category Slug"
                            value={attributes.category}
                            onChange={(value) => setAttributes({ category: value })}
                        />
                        <SelectControl
                            label="Order"
                            value={attributes.order}
                            options={[
                                { label: 'Ascending', value: 'ASC' },
                                { label: 'Descending', value: 'DESC' },
                            ]}
                            onChange={(value) => setAttributes({ order: value })}
                        />
                    </PanelBody>
                </InspectorControls>
                <p>📅 Event Grid Preview (Frontend only)</p>
            </>
        );
    },
    save: () => null, // dynamic block
});
