<?php

namespace OxygenSuite\OxygenErgani\Ergani\Concerns;

use DateTime;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Documents\CancelSubmittedDocument;
use OxygenSuite\OxygenErgani\Http\Documents\Documents;
use OxygenSuite\OxygenErgani\Http\Documents\LookupSubmissions;

trait ManagesDocuments
{
    /**
     * Cancel a previously submitted document.
     *
     * Currently supported document types:
     * - Work Time Organization - Leave (WTOLeave)
     * - Work Time Organization - Leave Correction (WTOLeaveC)
     *
     * @param string $documentType The document type code (e.g., 'WTOLeave')
     * @param string $protocol Protocol number from submission response
     * @param DateTime|int|string $submissionDate Submission date (DateTime, Ymd integer, or Ymd string)
     *
     * @throws ErganiException
     */
    public function cancelDocument(string $documentType, string $protocol, DateTime|int|string $submissionDate): bool
    {
        return (new CancelSubmittedDocument($this->accessToken, $this->environment, $this->config))
            ->handle($documentType, $protocol, $submissionDate);
    }

    /**
     * Retrieve all available submissions/document types.
     *
     * @return array<string, mixed>
     * @throws ErganiException
     */
    public function getSubmissions(): array
    {
        return (new LookupSubmissions($this->accessToken, $this->environment, $this->config))
            ->handle();
    }

    /**
     * Get schema for a specific document type.
     *
     * @param class-string<Documents> $documentClass Fully qualified class name of the document
     *
     * @return array<string, mixed>
     * @throws ErganiException
     */
    public function getSchema(string $documentClass): array
    {
        /** @var Documents $document */
        $document = new $documentClass($this->accessToken, $this->environment, $this->config);

        return $document->schema();
    }

    /**
     * Retrieve PDF of a submitted document.
     *
     * @param class-string<Documents> $documentClass Fully qualified class name of the document
     * @param string $protocol Protocol number from submission response
     * @param DateTime|int|string $submittedDate Submission date (DateTime, Ymd integer, or Ymd string)
     *
     * @return string Base64-encoded PDF content
     * @throws ErganiException
     */
    public function getDocumentPdf(string $documentClass, string $protocol, DateTime|int|string $submittedDate): string
    {
        /** @var Documents $document */
        $document = new $documentClass($this->accessToken, $this->environment, $this->config);

        return $document->pdf($protocol, $submittedDate);
    }
}
