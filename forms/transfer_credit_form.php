<!-- Modern Transfer Credit Form - Self-contained with scoped styles -->
<style>
    /* Scoped styles for this form only */
    .modern-form-container {
        max-width: 42rem;
        margin-left: auto;
        margin-right: auto;
    }

    .modern-card {
        background-color: #ffffff;
        border-radius: 0.5rem;
        /* box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); */
        padding: 2rem;
    }

    .modern-header {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
    }

    .modern-icon-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 3rem;
        height: 3rem;
        border-radius: 9999px;
        background-color: #DBEAFE;
        margin-right: 1rem;
    }

    .modern-icon-badge svg {
        width: 1.5rem;
        height: 1.5rem;
        color: #2563EB;
    }

    .modern-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .modern-form-group {
        margin-bottom: 1.5rem;
    }

    .modern-label {
        display: block;
        margin-bottom: 0.75rem;
        font-size: 1.25rem;
        font-weight: 600;
        color: #111827;
    }

    .modern-input-wrapper {
        position: relative;
    }

    .modern-input-icon {
        position: absolute;
        top: 50%;
        left: 1.25rem;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .modern-input-icon svg {
        width: 1.5rem;
        height: 1.5rem;
        color: #6B7280;
    }

    .modern-input {
        background-color: #F9FAFB;
        border: 1px solid #D1D5DB;
        color: #111827;
        font-size: 1.125rem;
        border-radius: 0.5rem;
        display: block;
        width: 100%;
        padding: 1rem 1rem 1rem 3.5rem;
        transition: all 0.2s;
    }

    .modern-input:focus {
        outline: none;
        border-color: #3B82F6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .modern-input::placeholder {
        color: #9CA3AF;
    }

    .modern-button {
        width: 100%;
        color: #ffffff;
        background: linear-gradient(to right, #3B82F6, #2563EB, #1D4ED8);
        font-weight: 600;
        border-radius: 0.5rem;
        font-size: 1.25rem;
        padding: 1.125rem 2rem;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.5);
        transition: all 0.3s;
    }

    .modern-button:hover {
        background: linear-gradient(to bottom right, #3B82F6, #2563EB, #1D4ED8);
        box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.5);
        transform: scale(1.02);
    }

    .modern-button svg {
        width: 1.5rem;
        height: 1.5rem;
        margin-right: 0.75rem;
    }

    .modern-button-wrapper {
        padding-top: 1rem;
    }

    /* Payment Status Toggle Styles */
    .payment-status-option {
        flex: 1;
        cursor: pointer;
    }

    .payment-status-option input[type="radio"] {
        display: none;
    }

    .payment-status-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.875rem 1.25rem;
        border: 2px solid #D1D5DB;
        border-radius: 0.5rem;
        background-color: #F9FAFB;
        transition: all 0.2s;
        font-size: 1.125rem;
        font-weight: 600;
        color: #6B7280;
    }

    .payment-status-option input[type="radio"]:checked + .payment-status-label.paid-label {
        background-color: #10b981;
        border-color: #059669;
        color: #ffffff;
    }

    .payment-status-option input[type="radio"]:checked + .payment-status-label.unpaid-label {
        background-color: #f59e0b;
        border-color: #d97706;
        color: #ffffff;
    }

    .payment-status-label:hover {
        border-color: #3B82F6;
    }

    .payment-status-option input[type="radio"]:checked + .payment-status-label svg {
        stroke: #ffffff;
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .modern-card {
            background-color: #1e293b;
            /* box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5), 0 4px 6px -2px rgba(0, 0, 0, 0.3); */
        }

        .modern-icon-badge {
            background-color: #1e40af;
        }

        .modern-icon-badge svg {
            color: #93c5fd;
        }

        .modern-title {
            color: #f1f5f9;
        }

        .modern-label {
            color: #f1f5f9;
        }

        .modern-input {
            background-color: #0f172a;
            border-color: #374151;
            color: #f1f5f9;
        }

        .modern-input:focus {
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .modern-input::placeholder {
            color: #6b7280;
        }

        .modern-input-icon svg {
            color: #9ca3af;
        }

        .payment-status-label {
            background-color: #0f172a;
            border-color: #374151;
            color: #9ca3af;
        }

        .payment-status-option input[type="radio"]:checked + .payment-status-label.paid-label {
            background-color: #059669;
            border-color: #047857;
            color: #ffffff;
        }

        .payment-status-option input[type="radio"]:checked + .payment-status-label.unpaid-label {
            background-color: #d97706;
            border-color: #b45309;
            color: #ffffff;
        }
    }
</style>

<div class="modern-form-container">
    <div class="modern-card">
        <!-- Header -->
        <div class="modern-header">
            <div class="modern-icon-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="modern-title">Refill Credit</h2>
        </div>

        <!-- Username Input -->
        <div class="modern-form-group">
            <label for="username" class="modern-label">User Name</label>
            <div class="modern-input-wrapper">
                <div class="modern-input-icon">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path>
                    </svg>
                </div>
                <input type="text" id="username" name="username" autocomplete="off" class="modern-input"
                    placeholder="Enter username" required />
            </div>
        </div>

        <!-- Amount Input -->
        <div class="modern-form-group">
            <label for="amount" class="modern-label">Amount</label>
            <div class="modern-input-wrapper">
                <div class="modern-input-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <input type="number" id="amount" name="jumlah" autocomplete="off" class="modern-input"
                    placeholder="Enter payment amount" min="0" step="0.01" required />
            </div>
        </div>

        <!-- Payment Status Toggle -->
        <div class="modern-form-group">
            <label class="modern-label">Payment Status</label>
            <div style="display: flex; gap: 12px; margin-top: 0.5rem;">
                <label class="payment-status-option">
                    <input type="radio" name="ispay" value="1" checked>
                    <span class="payment-status-label paid-label">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px; margin-right: 8px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Paid
                    </span>
                </label>
                <label class="payment-status-option">
                    <input type="radio" name="ispay" value="0">
                    <span class="payment-status-label unpaid-label">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px; margin-right: 8px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Unpaid
                    </span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="modern-button-wrapper">
            <button type="submit" class="modern-button">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Credit Now
            </button>
        </div>
    </div>
</div>