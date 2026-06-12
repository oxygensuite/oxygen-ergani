import { defineConfig } from 'vitepress'
import llmstxt from 'vitepress-plugin-llms'
import { groupIconMdPlugin, groupIconVitePlugin } from 'vitepress-plugin-group-icons'

export default defineConfig({
  title: 'Oxygen Ergani',
  description: 'PHP package for interacting with Greece\'s ERGANI system',
  base: '/oxygen-ergani/',

  cleanUrls: true,
  lastUpdated: true,

  sitemap: {
    hostname: 'https://oxygensuite.github.io/oxygen-ergani/'
  },

  vite: {
    plugins: [
      llmstxt({ domain: 'https://oxygensuite.github.io' }),
      groupIconVitePlugin()
    ]
  },

  head: [
    ['link', { rel: 'icon', type: 'image/svg+xml', href: '/logo.svg' }],
  ],

  themeConfig: {
    logo: '/logo.svg',

    nav: [
      { text: 'Guide', link: '/guide/getting-started' },
      { text: 'API Reference', link: '/api/' },
      {
        text: 'Resources',
        items: [
          { text: 'GitHub', link: 'https://github.com/oxygensuite/oxygen-ergani' },
          { text: 'Packagist', link: 'https://packagist.org/packages/oxygensuite/oxygen-ergani' },
        ]
      }
    ],

    sidebar: {
      '/guide/': [
        {
          text: 'Introduction',
          items: [
            { text: 'Getting Started', link: '/guide/getting-started' },
            { text: 'Installation', link: '/guide/installation' },
            { text: 'Configuration', link: '/guide/configuration' },
          ]
        },
        {
          text: 'Authentication',
          items: [
            { text: 'Token Management', link: '/guide/token-management' },
            { text: 'Custom Token Manager', link: '/guide/custom-token-manager' },
          ]
        },
        {
          text: 'Documents',
          items: [
            { text: 'Work Cards', link: '/guide/work-cards' },
            { text: 'Work Time', link: '/guide/work-time' },
            {
              text: 'Hiring (E3)',
              collapsed: false,
              items: [
                { text: 'Overview', link: '/guide/hiring/' },
                { text: 'New Hire (E3N)', link: '/guide/hiring/new' },
                { text: 'Transfer (E3M)', link: '/guide/hiring/transfer' },
                { text: 'Lending (E3D)', link: '/guide/hiring/lending' },
                { text: 'Borrowed (E3PD)', link: '/guide/hiring/borrowed' },
              ]
            },
            {
              text: 'Termination (E5)',
              collapsed: false,
              items: [
                { text: 'Overview', link: '/guide/termination/' },
                { text: 'Voluntary (E5N)', link: '/guide/termination/voluntary' },
                { text: 'Notification (E5O)', link: '/guide/termination/notification' },
                { text: 'After Notification (E5AO)', link: '/guide/termination/after-notification' },
                { text: 'Death (E5D)', link: '/guide/termination/death' },
                { text: 'Compensated Exit (E5E)', link: '/guide/termination/compensated-exit' },
                { text: 'Voluntary Retirement (E5S)', link: '/guide/termination/voluntary-retirement' },
                { text: 'Mandatory Retirement (E5DS)', link: '/guide/termination/mandatory-retirement' },
              ]
            },
            {
              text: 'Dismissal (E6)',
              collapsed: false,
              items: [
                { text: 'Overview', link: '/guide/dismissal/' },
                { text: 'Without Notice (E6NXP)', link: '/guide/dismissal/without-notice' },
                { text: 'With Notice (E6NMP)', link: '/guide/dismissal/with-notice' },
                { text: 'Retirement (E6SXP)', link: '/guide/dismissal/retirement' },
                { text: 'End of Loan (E6LD)', link: '/guide/dismissal/end-of-loan' },
                { text: 'Trial Period (E6LT)', link: '/guide/dismissal/trial-period' },
                { text: 'Transfer (E6M)', link: '/guide/dismissal/transfer' },
              ]
            },
            { text: 'Fixed-Term (E7)', link: '/guide/fixed-term' },
            { text: 'Modifications (MA)', link: '/guide/modifications' },
            { text: 'Construction (E12)', link: '/guide/construction' },
            { text: 'Sixth Day', link: '/guide/sixth-day' },
            { text: 'Pre-Announcement', link: '/guide/pre-announcement' },
            { text: 'Internship (E3.5)', link: '/guide/internship' },
          ]
        },
        {
          text: 'Advanced',
          items: [
            { text: 'Services & Queries', link: '/guide/services' },
            { text: 'Cancel Submissions', link: '/guide/cancel-submissions' },
            { text: 'Error Handling', link: '/guide/error-handling' },
            { text: 'Model Factories', link: '/guide/factories' },
            { text: 'CLI Tools', link: '/guide/cli-tools' },
          ]
        }
      ],
      '/api/': [
        {
          text: 'API Reference',
          items: [
            { text: 'Overview', link: '/api/' },
            { text: 'Ergani Facade', link: '/api/ergani' },
          ]
        },
        {
          text: 'Enums',
          collapsed: false,
          items: [
            { text: 'Overview', link: '/api/enums/' },
            { text: 'Personal', link: '/api/enums/personal' },
            { text: 'Employment', link: '/api/enums/employment' },
            { text: 'Work Time', link: '/api/enums/work-time' },
            { text: 'Loan/Borrowing', link: '/api/enums/loan' },
            { text: 'Termination', link: '/api/enums/termination' },
            { text: 'Administrative', link: '/api/enums/administrative' },
          ]
        },
        {
          text: 'Other',
          items: [
            { text: 'Responses', link: '/api/responses' },
            { text: 'Exceptions', link: '/api/exceptions' },
          ]
        }
      ]
    },

    socialLinks: [
      { icon: 'github', link: 'https://github.com/oxygensuite/oxygen-ergani' }
    ],

    footer: {
      message: 'Released under the MIT License.',
      copyright: 'Copyright 2025 © Oxygen Suite'
    },

    search: {
      provider: 'local'
    },

    editLink: {
      pattern: 'https://github.com/oxygensuite/oxygen-ergani/edit/master/docs/:path',
      text: 'Edit this page on GitHub'
    }
  },

  markdown: {
    lineNumbers: true,
    config(md) {
      md.use(groupIconMdPlugin)
    }
  }
})
