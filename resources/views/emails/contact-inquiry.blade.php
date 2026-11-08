<h1>New Contact us inquiry</h1>
<p><strong>Name:</strong> {{ $inquiry['name'] }}</p>
<p><strong>Email:</strong> {{ $inquiry['email'] }}</p>
<p><strong>Company:</strong> {{ $inquiry['company'] ?? '?' }}</p>
<p><strong>Subject:</strong> {{ $inquiry['subject'] }}</p>
<p style="white-space: pre-wrap">{{ $inquiry['message'] }}</p>
