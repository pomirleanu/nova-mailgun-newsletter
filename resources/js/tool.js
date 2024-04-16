import MailgunNewsletterTool from './pages/MailgunNewsletterTool'

Nova.booting((app, store) => {
  Nova.inertia('MailgunNewsletter', MailgunNewsletterTool)
})
