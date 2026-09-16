import { test, expect } from '@playwright/test';

test('homepage loads in the browser', async ({ page }) => {
    await page.goto('/');

    await expect(page).toHaveTitle('JARA');
    await expect(page.getByRole('heading', { name: "Let's get started" })).toBeVisible();
});

test('unknown route returns a 404 page', async ({ page }) => {
    const response = await page.goto('/halaman-tidak-ada');

    expect(response.status()).toBe(404);
});