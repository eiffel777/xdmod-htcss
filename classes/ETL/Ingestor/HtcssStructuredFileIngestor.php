<?php
/* ------------------------------------------------------------------------------------------
 * Ingestor for data loaded from a file. The defintion file contains a list
 * of column names and data records to be loaded (either in that file or in an
 * external file).
 *
 * @author Greg Dean
 * ------------------------------------------------------------------------------------------
 */

namespace ETL\Ingestor;

use Psr\Log\LoggerInterface;
use Exception;
use ETL\iAction;
use ETL\aOptions;
use ETL\Configuration\EtlConfiguration;
use ETL\EtlOverseerOptions;
use ETL\DataEndpoint\iStructuredFile;

class HtcssStructuredFileIngestor extends StructuredFileIngestor implements iAction
{
    /** -----------------------------------------------------------------------------------------
     * The custom insert values component is an object that allows us to specify a
     * subquery to use when inserting data rather than the raw source value. If the
     * destination column is present as a key in the object, the key's value will be used.
     *
     * @var array|null
     * ------------------------------------------------------------------------------------------
     */

    protected $customInsertValuesComponents = null;

    /** -----------------------------------------------------------------------------------------
     * @see iAction::__construct()
     * ------------------------------------------------------------------------------------------
     */

    public function __construct(aOptions $options, EtlConfiguration $etlConfig, LoggerInterface $logger = null)
    {
        parent::__construct($options, $etlConfig, $logger);
    }  // __construct()

    /** -----------------------------------------------------------------------------------------
     * @see iAction::initialize()
     *
     * @throws Exception if any query data was not int the correct format.
     * ------------------------------------------------------------------------------------------
     */

    public function initialize(EtlOverseerOptions $etlOverseerOptions = null)
    {
        return parent::initialize($etlOverseerOptions);

    }  // initialize()

    /** -----------------------------------------------------------------------------------------
     * @see aIngestor::_execute()
     * ------------------------------------------------------------------------------------------
     */

    // @codingStandardsIgnoreLine
    protected function _execute()
    {
        return parent::_execute();

    }  // _execute()

    /**
     * Build up a parameter list suitable for an SQL query. The parameters must be in the proper
     * order as expected by the field list of the query (this mapping information is stored in
     * $destinationFieldIdToSourceFieldMap). Note that the same source value may be used multiple
     * times in the query.
     *
     * @param $sourceRecord The current record from the source endpoint (must be Traversable but
     *   may not explicitly implement Traversable such as an array or stdClass)
     * @param array $destinationFieldIdToSourceFieldMap A mapping between the parameter position
     *   (index) in the SQL statement and the source fields so we cam properly build the SQL
     *   parameter list in the correct order.
     * @param array $sourceTemplate Templates for source field values containing pre-determined
     *   values such as variables or macros.
     * @param array $simpleSourceFields Scalar source fields that map to source fields.
     * @param array $complexSourceFields Complex source fields that must be evaluated by the source
     *   endpoint
     *
     * @return array A list of values to use as SQL parameters in the proper order corresponding
     *   to the SQL query parameters.
     */

    private function generateParametersFromSourceRecord(
        $sourceRecord,
        array $destinationFieldIdToSourceFieldMap,
        array $sourceTemplate,
        array $simpleSourceFields,
        array $complexSourceFields
    ) {
        return parent::generateParametersFromSourceRecord($sourceRecord, $destinationFieldIdToSourceFieldMap, $sourceTemplate, $simpleSourceFields, $complexSourceFields);

    }  // generateParametersFromSourceRecord()

    /** -----------------------------------------------------------------------------------------
     * @see aIngestor::performPreExecuteTasks
     * ------------------------------------------------------------------------------------------
     */

    protected function performPreExecuteTasks()
    {
        parent::performPreExecuteTasks();
        $this->logger->debug("Starting Transaction");
        $this->destinationHandle->beginTransaction();

        return true;
    }

    /** -----------------------------------------------------------------------------------------
     * @see aIngestor::performPostExecuteTasks
     * ------------------------------------------------------------------------------------------
     */
    protected function performPostExecuteTasks($totalRecordsProcessed = NULL)
    {
        $this->logger->debug("Committing Transaction");
        $this->destinationHandle->commit();
        parent::performPostExecuteTasks($totalRecordsProcessed);

        return true;
    }
}  // class HtcssStructuredFileIngestor
