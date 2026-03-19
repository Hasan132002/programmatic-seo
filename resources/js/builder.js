import grapesjs from 'grapesjs';
import 'grapesjs/dist/css/grapes.min.css';
import gjsBlocksBasic from 'grapesjs-blocks-basic';
import gjsPluginForms from 'grapesjs-plugin-forms';
import gjsTabs from 'grapesjs-tabs';
import gjsCustomCode from 'grapesjs-custom-code';
import gjsStyleBg from 'grapesjs-style-bg';

window.initGrapesJS = function(containerId, config = {}) {
    const editor = grapesjs.init({
        container: `#${containerId}`,
        height: '100%',
        width: 'auto',
        fromElement: false,
        storageManager: false,
        plugins: [
            gjsBlocksBasic,
            gjsPluginForms,
            gjsTabs,
            gjsCustomCode,
            gjsStyleBg,
        ],
        pluginsOpts: {
            [gjsBlocksBasic]: {
                flexGrid: true,
                blocks: ['column1', 'column2', 'column3', 'column3-7', 'text', 'link', 'image', 'video', 'map'],
                category: 'Basic',
            },
            [gjsPluginForms]: {
                blocks: ['form', 'input', 'textarea', 'select', 'button', 'label', 'checkbox', 'radio'],
            },
            [gjsTabs]: {
                tabsBlock: { category: 'Interactive' },
            },
            [gjsCustomCode]: {
                blockLabel: 'Custom Code',
                blockCustomCode: { category: 'Advanced' },
            },
            [gjsStyleBg]: {},
        },
        canvas: {
            styles: [
                'https://cdn.jsdelivr.net/npm/tailwindcss@2/dist/tailwind.min.css',
                'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap',
            ],
        },
        panels: { defaults: [] },
        blockManager: {
            appendTo: '#blocks-panel',
        },
        traitManager: {
            appendTo: '#traits-panel',
        },
        selectorManager: {
            appendTo: '#selectors-panel',
        },
        styleManager: {
            appendTo: '#styles-panel',
            sectors: [
                {
                    name: 'Layout',
                    open: true,
                    properties: [
                        'display',
                        {
                            type: 'composite',
                            property: 'flex',
                            label: 'Flex',
                            properties: [
                                { property: 'flex-direction', type: 'radio', defaults: 'row', options: [
                                    { id: 'row', label: 'Row' },
                                    { id: 'column', label: 'Col' },
                                ]},
                                { property: 'justify-content', type: 'select', defaults: 'flex-start', options: [
                                    { id: 'flex-start', label: 'Start' },
                                    { id: 'center', label: 'Center' },
                                    { id: 'flex-end', label: 'End' },
                                    { id: 'space-between', label: 'Between' },
                                    { id: 'space-around', label: 'Around' },
                                ]},
                                { property: 'align-items', type: 'select', defaults: 'stretch', options: [
                                    { id: 'stretch', label: 'Stretch' },
                                    { id: 'flex-start', label: 'Start' },
                                    { id: 'center', label: 'Center' },
                                    { id: 'flex-end', label: 'End' },
                                ]},
                                { property: 'flex-wrap', type: 'radio', defaults: 'nowrap', options: [
                                    { id: 'nowrap', label: 'No' },
                                    { id: 'wrap', label: 'Yes' },
                                ]},
                                { property: 'gap' },
                            ],
                        },
                        'float', 'position', 'top', 'right', 'left', 'bottom',
                        'overflow', 'z-index',
                    ],
                },
                {
                    name: 'Size',
                    open: false,
                    properties: [
                        'width', 'min-width', 'max-width',
                        'height', 'min-height', 'max-height',
                    ],
                },
                {
                    name: 'Spacing',
                    open: false,
                    properties: [
                        'margin', 'padding',
                    ],
                },
                {
                    name: 'Typography',
                    open: false,
                    properties: [
                        {
                            property: 'font-family',
                            type: 'select',
                            defaults: 'Inter, system-ui, sans-serif',
                            options: [
                                { id: 'Inter, system-ui, sans-serif', label: 'Inter' },
                                { id: 'Georgia, serif', label: 'Georgia' },
                                { id: 'Arial, Helvetica, sans-serif', label: 'Arial' },
                                { id: "'Courier New', monospace", label: 'Courier New' },
                                { id: "'Times New Roman', serif", label: 'Times New Roman' },
                                { id: 'Verdana, sans-serif', label: 'Verdana' },
                                { id: 'Tahoma, sans-serif', label: 'Tahoma' },
                            ],
                        },
                        'font-size', 'font-weight', 'letter-spacing', 'line-height',
                        'color', 'text-align',
                        'text-decoration', 'text-transform',
                        'text-shadow',
                    ],
                },
                {
                    name: 'Background',
                    open: false,
                    properties: [
                        'background-color',
                        'background',
                    ],
                },
                {
                    name: 'Borders',
                    open: false,
                    properties: [
                        'border-radius',
                        'border',
                        'border-width', 'border-style', 'border-color',
                    ],
                },
                {
                    name: 'Effects',
                    open: false,
                    properties: [
                        'opacity', 'box-shadow',
                        'transition', 'transform', 'cursor',
                    ],
                },
            ],
        },
        layerManager: {
            appendTo: '#layers-panel',
        },
        deviceManager: {
            devices: [
                { name: 'Desktop', width: '' },
                { name: 'Tablet', width: '768px', widthMedia: '992px' },
                { name: 'Mobile', width: '375px', widthMedia: '480px' },
            ],
        },
    });

    // =========================================================================
    // Custom pSEO Blocks (richer, more styled, user-friendly)
    // =========================================================================
    const bm = editor.BlockManager;

    // --- Hero Blocks ---
    bm.add('hero-gradient', {
        label: 'Hero Banner',
        category: 'Layout',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="18" rx="2"/><line x1="6" y1="9" x2="18" y2="9"/><line x1="8" y1="13" x2="16" y2="13"/><rect x="9" y="16" width="6" height="2" rx="1"/></svg>`,
        content: `<section style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 100px 40px; text-align: center; position: relative; overflow: hidden;">
            <div style="position: relative; z-index: 1; max-width: 800px; margin: 0 auto;">
                <h1 style="font-family: Inter, system-ui, sans-serif; font-size: 3em; font-weight: 800; color: white; margin-bottom: 16px; line-height: 1.2;">Your Amazing Headline Here</h1>
                <p style="font-family: Inter, system-ui, sans-serif; font-size: 1.25em; color: rgba(255,255,255,0.9); margin-bottom: 32px; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.6;">A compelling subtitle that describes your service, product, or offer in a few words.</p>
                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <a href="#" style="display: inline-block; padding: 14px 32px; background: white; color: #667eea; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 1em; box-shadow: 0 4px 14px rgba(0,0,0,0.15);">Get Started Free</a>
                    <a href="#" style="display: inline-block; padding: 14px 32px; background: rgba(255,255,255,0.15); color: white; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 1em; border: 2px solid rgba(255,255,255,0.3);">Learn More</a>
                </div>
            </div>
        </section>`,
    });

    bm.add('hero-split', {
        label: 'Hero Split',
        category: 'Layout',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="9" height="18" rx="1"/><rect x="13" y="3" width="9" height="18" rx="1"/></svg>`,
        content: `<section style="display: flex; align-items: center; gap: 60px; padding: 80px 40px; max-width: 1200px; margin: 0 auto; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <span style="display: inline-block; padding: 6px 16px; background: #ede9fe; color: #7c3aed; border-radius: 20px; font-size: 0.85em; font-weight: 600; margin-bottom: 16px;">Featured</span>
                <h1 style="font-family: Inter, system-ui, sans-serif; font-size: 2.8em; font-weight: 800; color: #0f172a; line-height: 1.15; margin-bottom: 20px;">Build Something Amazing Today</h1>
                <p style="font-size: 1.15em; color: #64748b; line-height: 1.7; margin-bottom: 28px;">Start building your next project with our powerful tools and beautiful templates. No coding required.</p>
                <a href="#" style="display: inline-block; padding: 14px 36px; background: #4f46e5; color: white; border-radius: 10px; text-decoration: none; font-weight: 700; box-shadow: 0 4px 14px rgba(79,70,229,0.4);">Get Started</a>
            </div>
            <div style="flex: 1; min-width: 300px;">
                <div style="background: linear-gradient(135deg, #c7d2fe, #e0e7ff); border-radius: 20px; height: 400px; display: flex; align-items: center; justify-content: center; color: #6366f1; font-size: 1.2em;">Image / Visual</div>
            </div>
        </section>`,
    });

    // --- Section Blocks ---
    bm.add('section-centered', {
        label: 'Section',
        category: 'Layout',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><line x1="7" y1="9" x2="17" y2="9"/><line x1="9" y1="13" x2="15" y2="13"/></svg>`,
        content: `<section style="padding: 80px 40px; max-width: 1200px; margin: 0 auto;">
            <div style="text-align: center; max-width: 700px; margin: 0 auto 48px;">
                <h2 style="font-family: Inter, system-ui, sans-serif; font-size: 2.2em; font-weight: 800; color: #0f172a; margin-bottom: 16px;">Section Heading</h2>
                <p style="font-size: 1.1em; color: #64748b; line-height: 1.7;">Write a brief description about this section. Explain what it covers and why it matters to your visitors.</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px;">
                <div style="padding: 32px; background: #f8fafc; border-radius: 16px; text-align: center;">
                    <div style="width: 56px; height: 56px; background: #ede9fe; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: #7c3aed; font-size: 1.5em;">1</div>
                    <h3 style="font-size: 1.2em; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Feature One</h3>
                    <p style="color: #64748b; font-size: 0.95em; line-height: 1.6;">Brief description of this feature and its benefits.</p>
                </div>
                <div style="padding: 32px; background: #f8fafc; border-radius: 16px; text-align: center;">
                    <div style="width: 56px; height: 56px; background: #dbeafe; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: #2563eb; font-size: 1.5em;">2</div>
                    <h3 style="font-size: 1.2em; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Feature Two</h3>
                    <p style="color: #64748b; font-size: 0.95em; line-height: 1.6;">Brief description of this feature and its benefits.</p>
                </div>
                <div style="padding: 32px; background: #f8fafc; border-radius: 16px; text-align: center;">
                    <div style="width: 56px; height: 56px; background: #dcfce7; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: #16a34a; font-size: 1.5em;">3</div>
                    <h3 style="font-size: 1.2em; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Feature Three</h3>
                    <p style="color: #64748b; font-size: 0.95em; line-height: 1.6;">Brief description of this feature and its benefits.</p>
                </div>
            </div>
        </section>`,
    });

    // --- pSEO Content Blocks ---
    bm.add('comparison-table', {
        label: 'Comparison Table',
        category: 'Content',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="12" y1="3" x2="12" y2="21"/></svg>`,
        content: `<div style="overflow-x: auto; margin: 24px 0;">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <thead>
                    <tr style="background: linear-gradient(135deg, #4f46e5, #6366f1); color: white;">
                        <th style="padding: 16px 20px; text-align: left; font-weight: 600;">Feature</th>
                        <th style="padding: 16px 20px; text-align: center; font-weight: 600;">Product A</th>
                        <th style="padding: 16px 20px; text-align: center; font-weight: 600;">Product B</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background: white;"><td style="padding: 14px 20px; border-bottom: 1px solid #f1f5f9; font-weight: 500;">Price</td><td style="padding: 14px 20px; text-align: center; border-bottom: 1px solid #f1f5f9;">$299</td><td style="padding: 14px 20px; text-align: center; border-bottom: 1px solid #f1f5f9;">$349</td></tr>
                    <tr style="background: #f8fafc;"><td style="padding: 14px 20px; border-bottom: 1px solid #f1f5f9; font-weight: 500;">Rating</td><td style="padding: 14px 20px; text-align: center; border-bottom: 1px solid #f1f5f9;">4.5/5 ⭐</td><td style="padding: 14px 20px; text-align: center; border-bottom: 1px solid #f1f5f9;">4.3/5 ⭐</td></tr>
                    <tr style="background: white;"><td style="padding: 14px 20px; border-bottom: 1px solid #f1f5f9; font-weight: 500;">Warranty</td><td style="padding: 14px 20px; text-align: center; border-bottom: 1px solid #f1f5f9;">2 Years</td><td style="padding: 14px 20px; text-align: center; border-bottom: 1px solid #f1f5f9;">1 Year</td></tr>
                    <tr style="background: #f8fafc;"><td style="padding: 14px 20px; font-weight: 500;">Free Shipping</td><td style="padding: 14px 20px; text-align: center; color: #16a34a; font-weight: 600;">✓ Yes</td><td style="padding: 14px 20px; text-align: center; color: #dc2626; font-weight: 600;">✗ No</td></tr>
                </tbody>
            </table>
        </div>`,
    });

    bm.add('listing-grid', {
        label: 'Listing Grid',
        category: 'Content',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="2" width="8" height="8" rx="2"/><rect x="14" y="2" width="8" height="8" rx="2"/><rect x="2" y="14" width="8" height="8" rx="2"/><rect x="14" y="14" width="8" height="8" rx="2"/></svg>`,
        content: `<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; padding: 24px 0;">
            <div style="border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; transition: box-shadow 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                <div style="height: 180px; background: linear-gradient(135deg, #c7d2fe, #e0e7ff); display: flex; align-items: center; justify-content: center; color: #6366f1;">Image</div>
                <div style="padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <h3 style="font-size: 1.1em; font-weight: 700; color: #0f172a;">Business Name</h3>
                        <span style="background: #dcfce7; color: #16a34a; padding: 2px 10px; border-radius: 12px; font-size: 0.8em; font-weight: 600;">4.8 ★</span>
                    </div>
                    <p style="color: #64748b; font-size: 0.9em; margin-bottom: 12px; line-height: 1.5;">Brief description of this business and what they offer to customers.</p>
                    <a href="#" style="color: #4f46e5; font-weight: 600; font-size: 0.9em; text-decoration: none;">View Details →</a>
                </div>
            </div>
            <div style="border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                <div style="height: 180px; background: linear-gradient(135deg, #fecaca, #fde68a); display: flex; align-items: center; justify-content: center; color: #dc2626;">Image</div>
                <div style="padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <h3 style="font-size: 1.1em; font-weight: 700; color: #0f172a;">Business Name</h3>
                        <span style="background: #dcfce7; color: #16a34a; padding: 2px 10px; border-radius: 12px; font-size: 0.8em; font-weight: 600;">4.6 ★</span>
                    </div>
                    <p style="color: #64748b; font-size: 0.9em; margin-bottom: 12px; line-height: 1.5;">Brief description of this business and what they offer to customers.</p>
                    <a href="#" style="color: #4f46e5; font-weight: 600; font-size: 0.9em; text-decoration: none;">View Details →</a>
                </div>
            </div>
            <div style="border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                <div style="height: 180px; background: linear-gradient(135deg, #bbf7d0, #a5f3fc); display: flex; align-items: center; justify-content: center; color: #16a34a;">Image</div>
                <div style="padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <h3 style="font-size: 1.1em; font-weight: 700; color: #0f172a;">Business Name</h3>
                        <span style="background: #dcfce7; color: #16a34a; padding: 2px 10px; border-radius: 12px; font-size: 0.8em; font-weight: 600;">4.9 ★</span>
                    </div>
                    <p style="color: #64748b; font-size: 0.9em; margin-bottom: 12px; line-height: 1.5;">Brief description of this business and what they offer to customers.</p>
                    <a href="#" style="color: #4f46e5; font-weight: 600; font-size: 0.9em; text-decoration: none;">View Details →</a>
                </div>
            </div>
        </div>`,
    });

    bm.add('faq-accordion', {
        label: 'FAQ Accordion',
        category: 'Content',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="4" rx="1"/><rect x="3" y="10" width="18" height="4" rx="1"/><rect x="3" y="16" width="18" height="4" rx="1"/></svg>`,
        content: `<div style="max-width: 800px; margin: 40px auto; padding: 0 20px;">
            <h2 style="font-family: Inter, system-ui, sans-serif; font-size: 2em; font-weight: 800; text-align: center; color: #0f172a; margin-bottom: 40px;">Frequently Asked Questions</h2>
            <details style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 24px; margin-bottom: 12px; background: white;">
                <summary style="cursor: pointer; font-weight: 600; font-size: 1.05em; color: #0f172a; list-style: none; display: flex; justify-content: space-between; align-items: center;">What services do you offer?<span style="color: #94a3b8; font-size: 1.5em; line-height: 1;">+</span></summary>
                <p style="margin-top: 16px; color: #64748b; line-height: 1.7; padding-top: 16px; border-top: 1px solid #f1f5f9;">We offer a comprehensive range of services tailored to meet your needs. Contact us for a detailed consultation and customized solution.</p>
            </details>
            <details style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 24px; margin-bottom: 12px; background: white;">
                <summary style="cursor: pointer; font-weight: 600; font-size: 1.05em; color: #0f172a; list-style: none; display: flex; justify-content: space-between; align-items: center;">How much does it cost?<span style="color: #94a3b8; font-size: 1.5em; line-height: 1;">+</span></summary>
                <p style="margin-top: 16px; color: #64748b; line-height: 1.7; padding-top: 16px; border-top: 1px solid #f1f5f9;">Our pricing varies based on the scope and complexity of your project. We offer free estimates and competitive rates.</p>
            </details>
            <details style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 24px; margin-bottom: 12px; background: white;">
                <summary style="cursor: pointer; font-weight: 600; font-size: 1.05em; color: #0f172a; list-style: none; display: flex; justify-content: space-between; align-items: center;">How do I get started?<span style="color: #94a3b8; font-size: 1.5em; line-height: 1;">+</span></summary>
                <p style="margin-top: 16px; color: #64748b; line-height: 1.7; padding-top: 16px; border-top: 1px solid #f1f5f9;">Getting started is easy. Simply reach out through our contact form or give us a call. We'll guide you through the entire process.</p>
            </details>
        </div>`,
    });

    bm.add('testimonial-card', {
        label: 'Testimonial',
        category: 'Content',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>`,
        content: `<div style="background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 32px; margin: 20px 0; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
            <div style="display: flex; gap: 4px; margin-bottom: 16px; color: #f59e0b; font-size: 1.2em;">★★★★★</div>
            <p style="font-size: 1.1em; color: #334155; line-height: 1.7; margin-bottom: 20px;">"This service exceeded all our expectations. The team was professional, responsive, and delivered outstanding results. Highly recommend to anyone looking for quality."</p>
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #a78bfa); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.1em;">JD</div>
                <div>
                    <div style="font-weight: 700; color: #0f172a;">John Doe</div>
                    <div style="font-size: 0.9em; color: #94a3b8;">CEO, Company Name</div>
                </div>
            </div>
        </div>`,
    });

    bm.add('city-info-card', {
        label: 'City Info Card',
        category: 'Content',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
        content: `<div style="background: linear-gradient(135deg, #eff6ff, #ede9fe); border-radius: 20px; padding: 40px; margin: 24px 0;">
            <h2 style="font-family: Inter, system-ui, sans-serif; font-size: 1.8em; font-weight: 800; color: #0f172a; margin-bottom: 8px;">About City Name, State</h2>
            <p style="color: #64748b; margin-bottom: 24px;">Everything you need to know about services in this area.</p>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
                <div style="background: white; padding: 20px; border-radius: 14px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                    <div style="font-size: 1.8em; font-weight: 800; color: #4f46e5; margin-bottom: 4px;">150K+</div>
                    <div style="color: #94a3b8; font-size: 0.85em; font-weight: 500;">Population</div>
                </div>
                <div style="background: white; padding: 20px; border-radius: 14px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                    <div style="font-size: 1.8em; font-weight: 800; color: #16a34a; margin-bottom: 4px;">4.8</div>
                    <div style="color: #94a3b8; font-size: 0.85em; font-weight: 500;">Avg Rating</div>
                </div>
                <div style="background: white; padding: 20px; border-radius: 14px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                    <div style="font-size: 1.8em; font-weight: 800; color: #ea580c; margin-bottom: 4px;">50+</div>
                    <div style="color: #94a3b8; font-size: 0.85em; font-weight: 500;">Providers</div>
                </div>
                <div style="background: white; padding: 20px; border-radius: 14px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                    <div style="font-size: 1.8em; font-weight: 800; color: #0ea5e9; margin-bottom: 4px;">24/7</div>
                    <div style="color: #94a3b8; font-size: 0.85em; font-weight: 500;">Availability</div>
                </div>
            </div>
            <p style="color: #475569; line-height: 1.7;">This city offers a wide range of services to meet your needs. Our local providers are highly rated and ready to help you with any project.</p>
        </div>`,
    });

    bm.add('pricing-table', {
        label: 'Pricing Card',
        category: 'Content',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="16" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M12 7v0M9 14h6M10 17h4"/></svg>`,
        content: `<div style="border: 2px solid #e2e8f0; border-radius: 20px; padding: 40px; text-align: center; max-width: 380px; margin: 24px auto; background: white; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
            <span style="display: inline-block; padding: 4px 16px; background: #ede9fe; color: #7c3aed; border-radius: 20px; font-size: 0.85em; font-weight: 600; margin-bottom: 16px;">Most Popular</span>
            <h3 style="font-size: 1.5em; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Pro Plan</h3>
            <p style="color: #94a3b8; font-size: 0.95em; margin-bottom: 24px;">Best for growing businesses</p>
            <div style="margin-bottom: 32px;">
                <span style="font-size: 3.5em; font-weight: 900; color: #0f172a; line-height: 1;">$49</span>
                <span style="color: #94a3b8; font-size: 1.1em;">/month</span>
            </div>
            <ul style="list-style: none; padding: 0; margin: 0 0 32px; text-align: left;">
                <li style="padding: 12px 0; border-bottom: 1px solid #f1f5f9; color: #334155; display: flex; align-items: center; gap: 10px;"><span style="color: #16a34a; font-weight: bold;">✓</span> Unlimited pages</li>
                <li style="padding: 12px 0; border-bottom: 1px solid #f1f5f9; color: #334155; display: flex; align-items: center; gap: 10px;"><span style="color: #16a34a; font-weight: bold;">✓</span> AI content generation</li>
                <li style="padding: 12px 0; border-bottom: 1px solid #f1f5f9; color: #334155; display: flex; align-items: center; gap: 10px;"><span style="color: #16a34a; font-weight: bold;">✓</span> Custom domain</li>
                <li style="padding: 12px 0; color: #334155; display: flex; align-items: center; gap: 10px;"><span style="color: #16a34a; font-weight: bold;">✓</span> Priority support</li>
            </ul>
            <a href="#" style="display: block; padding: 14px 24px; background: linear-gradient(135deg, #4f46e5, #6366f1); color: white; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 1.05em; box-shadow: 0 4px 14px rgba(79,70,229,0.3);">Get Started</a>
        </div>`,
    });

    // --- Conversion Blocks ---
    bm.add('cta-banner', {
        label: 'CTA Banner',
        category: 'Conversion',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>`,
        content: `<section style="background: linear-gradient(135deg, #0f172a, #1e293b); padding: 80px 40px; text-align: center; border-radius: 20px; margin: 24px 0; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -60px; right: -60px; width: 200px; height: 200px; background: rgba(99,102,241,0.15); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -40px; left: -40px; width: 160px; height: 160px; background: rgba(139,92,246,0.1); border-radius: 50%;"></div>
            <div style="position: relative; z-index: 1; max-width: 600px; margin: 0 auto;">
                <h2 style="font-family: Inter, system-ui, sans-serif; font-size: 2.2em; font-weight: 800; color: white; margin-bottom: 16px;">Ready to Get Started?</h2>
                <p style="color: #94a3b8; font-size: 1.1em; margin-bottom: 32px; line-height: 1.6;">Join thousands of satisfied customers. Get a free consultation today.</p>
                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <a href="#" style="display: inline-block; padding: 14px 36px; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border-radius: 12px; text-decoration: none; font-weight: 700; box-shadow: 0 4px 14px rgba(99,102,241,0.4);">Contact Us</a>
                    <a href="tel:555-0123" style="display: inline-block; padding: 14px 36px; background: rgba(255,255,255,0.1); color: white; border-radius: 12px; text-decoration: none; font-weight: 600; border: 1px solid rgba(255,255,255,0.2);">Call (555) 012-3456</a>
                </div>
            </div>
        </section>`,
    });

    bm.add('contact-form-card', {
        label: 'Contact Form',
        category: 'Conversion',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><polyline points="3,7 12,13 21,7"/></svg>`,
        content: `<div style="max-width: 540px; margin: 24px auto; padding: 40px; background: white; border: 1px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
            <h3 style="font-family: Inter, system-ui, sans-serif; font-size: 1.6em; font-weight: 800; color: #0f172a; margin-bottom: 8px; text-align: center;">Get a Free Quote</h3>
            <p style="text-align: center; color: #94a3b8; margin-bottom: 28px;">Fill out the form and we'll get back to you within 24 hours.</p>
            <form>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 0.9em; font-weight: 600; color: #334155; margin-bottom: 6px;">First Name</label>
                        <input type="text" placeholder="John" style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.95em; outline: none; box-sizing: border-box;" />
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.9em; font-weight: 600; color: #334155; margin-bottom: 6px;">Last Name</label>
                        <input type="text" placeholder="Doe" style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.95em; outline: none; box-sizing: border-box;" />
                    </div>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 0.9em; font-weight: 600; color: #334155; margin-bottom: 6px;">Email</label>
                    <input type="email" placeholder="john@example.com" style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.95em; outline: none; box-sizing: border-box;" />
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 0.9em; font-weight: 600; color: #334155; margin-bottom: 6px;">Phone</label>
                    <input type="tel" placeholder="(555) 000-0000" style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.95em; outline: none; box-sizing: border-box;" />
                </div>
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 0.9em; font-weight: 600; color: #334155; margin-bottom: 6px;">Message</label>
                    <textarea rows="4" placeholder="Tell us about your project..." style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.95em; resize: vertical; outline: none; box-sizing: border-box;"></textarea>
                </div>
                <button type="submit" style="width: 100%; padding: 14px; background: linear-gradient(135deg, #4f46e5, #6366f1); color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 1.05em; cursor: pointer; box-shadow: 0 4px 14px rgba(79,70,229,0.3);">Send Message</button>
            </form>
        </div>`,
    });

    // --- Monetization Blocks ---
    bm.add('ad-placeholder', {
        label: 'Ad Slot',
        category: 'Monetization',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="18" rx="2"/><line x1="9" y1="10" x2="15" y2="10"/><line x1="10" y1="14" x2="14" y2="14"/></svg>`,
        content: `<div style="border: 2px dashed #cbd5e1; border-radius: 12px; padding: 32px; text-align: center; margin: 24px 0; background: #f8fafc;">
            <div style="color: #94a3b8; font-size: 0.9em; font-weight: 500;">Advertisement</div>
            <div style="color: #cbd5e1; font-size: 0.8em; margin-top: 4px;">728 x 90 Banner</div>
        </div>`,
    });

    bm.add('affiliate-product', {
        label: 'Affiliate Box',
        category: 'Monetization',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>`,
        content: `<div style="border: 1px solid #e2e8f0; border-radius: 16px; padding: 28px; margin: 24px 0; display: flex; gap: 24px; align-items: center; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #f1f5f9, #e2e8f0); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <span style="color: #94a3b8; font-size: 0.85em; font-weight: 500;">Product Image</span>
            </div>
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                    <span style="background: #dcfce7; color: #16a34a; padding: 2px 10px; border-radius: 10px; font-size: 0.75em; font-weight: 600;">Best Seller</span>
                </div>
                <h4 style="font-size: 1.2em; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Product Name</h4>
                <p style="color: #64748b; font-size: 0.9em; margin-bottom: 12px; line-height: 1.5;">Brief product description highlighting key features and benefits.</p>
                <div style="display: flex; align-items: center; gap: 16px;">
                    <span style="font-size: 1.4em; font-weight: 800; color: #0f172a;">$99.99</span>
                    <a href="#" style="display: inline-block; padding: 10px 24px; background: #059669; color: white; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 0.9em;">Check Price →</a>
                </div>
            </div>
        </div>`,
    });

    // --- Social Proof ---
    bm.add('testimonial-slider', {
        label: 'Testimonial Slider',
        category: 'Social Proof',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>`,
        content: `<section style="padding: 60px 40px; background: #f8fafc; border-radius: 20px; margin: 24px 0;">
            <h2 style="font-family: Inter, system-ui, sans-serif; font-size: 2em; font-weight: 800; text-align: center; color: #0f172a; margin-bottom: 40px;">What Our Customers Say</h2>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; max-width: 1100px; margin: 0 auto;">
                <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                    <div style="color: #f59e0b; margin-bottom: 12px;">★★★★★</div>
                    <p style="color: #334155; line-height: 1.6; margin-bottom: 16px; font-size: 0.95em;">"Absolutely fantastic service. Professional team that goes above and beyond expectations."</p>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #c7d2fe; display: flex; align-items: center; justify-content: center; color: #4f46e5; font-weight: 700;">AB</div>
                        <div><div style="font-weight: 600; color: #0f172a; font-size: 0.9em;">Alice Brown</div><div style="color: #94a3b8; font-size: 0.8em;">Homeowner</div></div>
                    </div>
                </div>
                <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                    <div style="color: #f59e0b; margin-bottom: 12px;">★★★★★</div>
                    <p style="color: #334155; line-height: 1.6; margin-bottom: 16px; font-size: 0.95em;">"Best in the business! Quick response times and quality work. Would definitely use again."</p>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #bbf7d0; display: flex; align-items: center; justify-content: center; color: #16a34a; font-weight: 700;">MS</div>
                        <div><div style="font-weight: 600; color: #0f172a; font-size: 0.9em;">Mike Smith</div><div style="color: #94a3b8; font-size: 0.8em;">Business Owner</div></div>
                    </div>
                </div>
                <div style="background: white; border-radius: 16px; padding: 28px; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                    <div style="color: #f59e0b; margin-bottom: 12px;">★★★★★</div>
                    <p style="color: #334155; line-height: 1.6; margin-bottom: 16px; font-size: 0.95em;">"Reliable, affordable, and top-notch quality. Cannot recommend enough to anyone in the area."</p>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #fecaca; display: flex; align-items: center; justify-content: center; color: #dc2626; font-weight: 700;">SJ</div>
                        <div><div style="font-weight: 600; color: #0f172a; font-size: 0.9em;">Sarah Johnson</div><div style="color: #94a3b8; font-size: 0.8em;">Property Manager</div></div>
                    </div>
                </div>
            </div>
        </section>`,
    });

    // --- Footer ---
    bm.add('footer-simple', {
        label: 'Footer',
        category: 'Layout',
        media: `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="17" width="20" height="4" rx="1"/><rect x="2" y="3" width="20" height="12" rx="2"/></svg>`,
        content: `<footer style="background: #0f172a; color: white; padding: 60px 40px 30px;">
            <div style="max-width: 1100px; margin: 0 auto;">
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 40px;">
                    <div>
                        <h3 style="font-size: 1.3em; font-weight: 800; margin-bottom: 12px;">Your Brand</h3>
                        <p style="color: #94a3b8; line-height: 1.7; font-size: 0.95em;">A brief description of your business and what makes you different from the competition.</p>
                    </div>
                    <div>
                        <h4 style="font-weight: 700; margin-bottom: 16px; font-size: 0.95em;">Services</h4>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="margin-bottom: 10px;"><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 0.9em;">Service One</a></li>
                            <li style="margin-bottom: 10px;"><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 0.9em;">Service Two</a></li>
                            <li style="margin-bottom: 10px;"><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 0.9em;">Service Three</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 style="font-weight: 700; margin-bottom: 16px; font-size: 0.95em;">Company</h4>
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            <li style="margin-bottom: 10px;"><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 0.9em;">About Us</a></li>
                            <li style="margin-bottom: 10px;"><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 0.9em;">Contact</a></li>
                            <li style="margin-bottom: 10px;"><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 0.9em;">Blog</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 style="font-weight: 700; margin-bottom: 16px; font-size: 0.95em;">Contact</h4>
                        <p style="color: #94a3b8; font-size: 0.9em; line-height: 1.8;">123 Main Street<br>City, State 12345<br>(555) 012-3456<br>info@example.com</p>
                    </div>
                </div>
                <div style="border-top: 1px solid #1e293b; padding-top: 24px; text-align: center;">
                    <p style="color: #64748b; font-size: 0.85em;">© 2026 Your Brand. All rights reserved.</p>
                </div>
            </div>
        </footer>`,
    });

    // Load existing blocks from database
    if (config.customBlocks) {
        config.customBlocks.forEach(block => {
            if (block.content) {
                bm.add(block.id, {
                    label: block.name,
                    category: block.category || 'Custom',
                    content: typeof block.content === 'string' ? block.content : JSON.stringify(block.content),
                });
            }
        });
    }

    // =========================================================================
    // Rich Text Editor enhancement
    // =========================================================================
    editor.on('rte:enable', () => {
        // RTE is enabled with grapesjs-preset-webpage
    });

    // =========================================================================
    // Keyboard shortcuts
    // =========================================================================
    editor.on('load', () => {
        editor.runCommand('sw-visibility');

        const cmdManager = editor.Commands;

        cmdManager.add('save-page', {
            run(ed) {
                const event = new CustomEvent('gjs:save', {
                    detail: {
                        html: ed.getHtml(),
                        css: ed.getCss(),
                        json: JSON.stringify(ed.getProjectData()),
                    }
                });
                document.dispatchEvent(event);
            }
        });

        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                cmdManager.run('save-page');
            }
        });
    });

    return editor;
};
