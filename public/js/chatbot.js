function farmChatbot() {
    return {
        open: false,
        input: '',
        sending: false,
        kb: null,
        messages: [
            { from: 'bot', text: "Hi! I'm the SmartHarvest assistant. Ask me about weather, market prices, messaging a DA officer, your farm profile, or RSBSA login." }
        ],

        async init() {
            try {
                const response = await fetch('/js/chatbot-kb.json');
                this.kb = await response.json();
            } catch (error) {
                console.error('Chatbot: failed to load knowledge base', error);
                this.kb = { fallback: "Sorry, I'm having trouble right now. Please try again later.", topics: [] };
            }
        },

        toggle() {
            this.open = !this.open;
        },

        send() {
            const text = this.input.trim();
            if (!text || this.sending) return;

            this.messages.push({ from: 'user', text });
            this.input = '';
            this.sending = true;

            const answer = this.matchAnswer(text);
            setTimeout(() => {
                this.messages.push({ from: 'bot', text: answer });
                this.sending = false;
                this.$nextTick(() => this.scrollToBottom());
            }, 300);

            this.$nextTick(() => this.scrollToBottom());
        },

        matchAnswer(text) {
            if (!this.kb) {
                return "Sorry, I'm still loading. Please try again in a moment.";
            }

            const normalized = text.toLowerCase().replace(/[^\w\s]/g, ' ');

            let bestTopic = null;
            let bestScore = 0;

            for (const topic of this.kb.topics) {
                let score = 0;
                for (const keyword of topic.keywords) {
                    if (normalized.includes(keyword.toLowerCase())) {
                        score++;
                    }
                }
                if (score > bestScore) {
                    bestScore = score;
                    bestTopic = topic;
                }
            }

            return bestTopic ? bestTopic.answer : this.kb.fallback;
        },

        scrollToBottom() {
            const container = this.$refs.chatMessages;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }
    };
}
