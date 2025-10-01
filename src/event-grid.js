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

                if (attributes.category) {
                    try {
                        const categories = await apiFetch({
                            path: `/wp-json/wp/v2/event-category?slug=${attributes.category}`
                        });
                        if (categories.length > 0) categoryId = categories[0].id;
                    } catch (err) {
                        console.error('Category fetch error', err);
                    }
                }

                let url = `/wp-json/proevent/v1/next?per_page=${attributes.limit}&order=${attributes.order}`;
                if (categoryId) url += `&category=${categoryId}`;

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
                    {loading && <Spinner />}
                    {!loading && events.length === 0 && <p>No events found.</p>}

                    <div className="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                        {events.map((event) => (
                            <div key={event.id} className="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition flex flex-col">
                                {event.featured_image && (
                                    <img src={event.featured_image} alt={event.title.rendered} className="w-full h-48 object-cover" loading="lazy" />
                                )}
                                <div className="p-6 flex-1 flex flex-col">
                                    <h3 className="text-xl font-semibold mb-2" dangerouslySetInnerHTML={{ __html: event.title.rendered }} />
                                    <p className="text-gray-500 mb-1"><strong>Date:</strong> {event.date || 'TBA'}</p>
                                    <p className="text-gray-500 mb-1"><strong>Time:</strong> {event.time || 'TBA'}</p>
                                    <p className="text-gray-500 mb-4"><strong>Location:</strong> {event.location || 'TBA'}</p>
                                    {event.registration_link && (
                                        <a href={event.registration_link} target="_blank" className="mt-auto inline-block bg-yellow-500 text-black font-semibold py-2 px-4 rounded hover:bg-yellow-600 transition">
                                            Register
                                        </a>
                                    )}
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </>
        );
    },
    save: () => null, // dynamic block
});
