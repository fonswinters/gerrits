<?php
namespace App\Attributes;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\Interfaces\HasLabelInterface;
use Komma\KMS\Core\Attributes\Traits\ExplanationTrait;
use Komma\KMS\Core\Attributes\Traits\LabelTrait;
use Komma\KMS\Core\Attributes\Traits\PlaceholderTextTrait;
use Komma\KMS\Core\Attributes\Traits\ReadOnlyTrait;
use Carbon\Carbon;
use InvalidArgumentException;

/**
 * Class TextField
 * @package App\Kms\Core\Attributes
 */
class TimePicker extends Attribute implements HasLabelInterface
{
    use LabelTrait;
    use PlaceholderTextTrait;
    use ReadOnlyTrait;
    use ExplanationTrait;

    public const TIME_FORMAT_12 = 12;
    public const TIME_FORMAT_24 = 24;

    private int $hoursStep;
    private int $minutesStep;

    /**
     * @return int
     */
    public function getHoursStep(): int
    {
        return $this->hoursStep ?? 1;
    }

    /**
     * @param int $hoursStep
     * @return TimePicker
     */
    public function setHoursStep(int $hoursStep): TimePicker
    {
        $this->hoursStep = $hoursStep;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinutesStep(): int
    {
        return $this->minutesStep ?? 1;
    }

    /**
     * @param int $minutesStep
     * @return TimePicker
     */
    public function setMinutesStep(int $minutesStep): TimePicker
    {
        $this->minutesStep = $minutesStep;
        return $this;
    }



    /** TimePicker format constant */
    private int $timeFormat = TimePicker::TIME_FORMAT_24;

    /**
     * Returns a view that visually represents this attribute
     *
     * @return string
     * @throws \Throwable
     */
    public function render(): string
    {
        return view('KMS::attributes.timePicker', [
            'attribute' => $this
        ])->render();
    }

    /**
     * @return mixed
     */
    public function getTimeFormat():int
    {
        return $this->timeFormat;
    }

    /**
     * @param mixed $format
     * @return TimePicker
     */
    public function setTimeFormat($format): TimePicker
    {
        switch ($format)
        {
            case TimePicker::TIME_FORMAT_12:
                throw new \RuntimeException('Not implemented');
                $this->timeFormat = TimePicker::TIME_FORMAT_12;
                break;
            case TimePicker::TIME_FORMAT_24:
            default:
                $this->timeFormat = TimePicker::TIME_FORMAT_24;
                break;
        }

        return $this;
    }


    /**
     * @param string $value The value associated with the attribute. Specified in this format:
     * 2018-02-23 00:00:00 (Y-m-d H:i:s) or this format: {"year":2018,"month":4,"day":14,"hour":18,"minute":2,"second":0}.
     * Internally always stores it as as the json format
     * @throws InvalidArgumentException
     * @return TimePicker
     */
    public function setValue(string $value): TimePicker
    {
        $decodedData = json_decode($value, true);
        if($decodedData === null) {
            //Value is a database date time string like this: 2018-02-23 00:00:00
            $dateTime = Carbon::createFromFormat('H:i:s', $value, 'Europe/Amsterdam');

            $dateArray = [
                'hour' => $dateTime->hour,
                'minute' => $dateTime->minute,
                'second' => $dateTime->second,
            ];

            $valueForParent = json_encode($dateArray);
            parent::setValue($valueForParent);
        } else {
            if(count(array_diff(['hour', 'minute', 'second'], array_keys($decodedData))) > 0) throw new \InvalidArgumentException('When you specify the value as a json string it must have these properties: hour, minute and second');
            parent::setValue($value);
        }

        return $this;
    }

    /**
     * @return string Returns the date in the format of 00:00:00 (H:i:s)
     */
    public function getValue(): string
    {
        if($this->value == "") throw new \RuntimeException('The value of the timepicker never was set');
        $dateArray = json_decode($this->value, true);
        $value = Carbon::now();
        $value->setHour(intval($dateArray['hour']));
        $value->setMinute(intval($dateArray['minute']));
        $value->setSecond(intval($dateArray['second']));
        $value->setTimezone('Europe/Amsterdam');

        return $value->toTimeString();
    }

    /**
     * @return string the value as json like this: {"hour":18,"minute":2,"second":0}
     */
    public function getValueAsJson():string
    {
        return $this->value;
    }
}