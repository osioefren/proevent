import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, TextControl, SelectControl, Spinner } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

registerBlockType('proevent/event-grid', {
    title: 'Event Grid',
    icon: 'calendar',
    category: 'widgets',
    attributes: {
        limit: { type: 'number', default: 6 },
        category: { type: 'string', default: '' }, // slug
        order: { type: 'string', default: 'ASC' },
    },
    edit: ({ attributes, setAttributes, className }) => {
        const [events, setEvents] = useState([]);
        const [loading, setLoading] = useState(true);

        useEffect(() => {
            setLoading(true);

            const fetchEvents = async () => {
                let categoryId = null;

                // If category slug is set, fetch the category ID
                if (attributes.category) {
                    try {
                        const categories = await apiFetch({
                            path: `/wp-json/wp/v2/event_category?slug=${attributes.category}`
                        });
                        if (categories.length > 0) {
                            categoryId = categories[0].id;
                        }
                    } catch (err) {
                        console.error('Category fetch error', err);
                    }
                }

                // Build event URL
                let url = `/wp-json/wp/v2/event?per_page=${attributes.limit}&order=${attributes.order}`;
                if (categoryId) {
                    url += `&categories=${categoryId}`;
                }

                // Fetch events
                try {
                    const data = await apiFetch({ path: url });
                    setEvents(data);
                } catch (err) {
                    console.error('Events fetch error', err);
                    setEvents([]);
                } finally {
                    setLoading(false);
                }
            };

            fetchEvents();
        }, [attributes.limit, attributes.category, attributes.order]);

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

                <div className={className}>
                    <p>📅 Event Grid Preview</p>
                    {loading && <Spinner />}
                    {!loading && events.length === 0 && <p>No events found.</p>}
                    <div className="event-grid">
                        {events.map((event) => (
                            <div key={event.id} className="event-item">
                                <h3 dangerouslySetInnerHTML={{ __html: event.title.rendered }} />
                                <p>{new Date(event.date).toLocaleDateString()}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </>
        );
    },
    save: () => null, // dynamic block
});
